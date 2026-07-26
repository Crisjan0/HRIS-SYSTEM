<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('travel_orders', 'ta_number')) {
                $table->string('ta_number')->nullable()->after('employee_id');
            }

            if (! Schema::hasColumn('travel_orders', 'approved_by_recordofficer')) {
                $table->foreignId('approved_by_recordofficer')->nullable()->after('status')->constrained('employees')->nullOnDelete();
            }

            if (! Schema::hasColumn('travel_orders', 'recordofficer_status')) {
                $table->string('recordofficer_status')->default('pending')->after('approved_by_recordofficer');
            }

            if (! Schema::hasColumn('travel_orders', 'recordofficer_remarks')) {
                $table->text('recordofficer_remarks')->nullable()->after('recordofficer_status');
            }

            if (! Schema::hasColumn('travel_orders', 'tar_deadline')) {
                $table->date('tar_deadline')->nullable()->after('rd_remarks');
            }

            if (! Schema::hasColumn('travel_orders', 'tar_status')) {
                $table->string('tar_status')->default('pending')->after('tar_deadline');
            }

            if (! Schema::hasColumn('travel_orders', 'tar_attachment_path')) {
                $table->string('tar_attachment_path')->nullable()->after('tar_status');
            }

            if (! Schema::hasColumn('travel_orders', 'tar_submitted_at')) {
                $table->timestamp('tar_submitted_at')->nullable()->after('tar_attachment_path');
            }

            if (! Schema::hasColumn('travel_orders', 'tar_remarks')) {
                $table->text('tar_remarks')->nullable()->after('tar_submitted_at');
            }
        });

        $dailySequences = [];

        DB::table('travel_orders')
            ->select('id', 'created_at', 'travel_date_end', 'status', 'rd_status', 'ta_number', 'recordofficer_status')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->each(function ($order) use (&$dailySequences) {
                $createdAt = $order->created_at ? Carbon::parse($order->created_at) : now();
                $dateKey = $createdAt->toDateString();
                $dailySequences[$dateKey] = ($dailySequences[$dateKey] ?? 0) + 1;

                DB::table('travel_orders')
                    ->where('id', $order->id)
                    ->update([
                        'ta_number' => $order->ta_number ?: sprintf('TA-%s-%03d', $createdAt->format('Y-m-d'), $dailySequences[$dateKey]),
                        'recordofficer_status' => $order->recordofficer_status ?: (($order->status === 'approved' || $order->rd_status === 'approved') ? 'approved' : 'pending'),
                        'tar_deadline' => $order->travel_date_end ? Carbon::parse($order->travel_date_end)->addDays(5)->toDateString() : null,
                        'tar_status' => DB::raw("COALESCE(tar_status, 'pending')"),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            foreach (['tar_remarks', 'tar_submitted_at', 'tar_attachment_path', 'tar_status', 'tar_deadline'] as $column) {
                if (Schema::hasColumn('travel_orders', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('travel_orders', 'approved_by_recordofficer')) {
                $table->dropConstrainedForeignId('approved_by_recordofficer');
            }

            foreach (['recordofficer_remarks', 'recordofficer_status', 'ta_number'] as $column) {
                if (Schema::hasColumn('travel_orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
