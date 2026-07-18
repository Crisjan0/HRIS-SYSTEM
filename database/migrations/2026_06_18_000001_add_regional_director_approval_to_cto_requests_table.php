<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cto_requests', function (Blueprint $table) {
            $table->foreignId('approved_by_regionaldirector')
                ->nullable()
                ->after('hrstaff_remarks')
                ->constrained('employees')
                ->nullOnDelete();
            $table->string('rd_status')->default('pending')->after('approved_by_regionaldirector');
            $table->text('rd_remarks')->nullable()->after('rd_status');
        });

        DB::table('cto_requests')
            ->where('status', 'approved')
            ->update(['rd_status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('cto_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by_regionaldirector');
            $table->dropColumn(['rd_status', 'rd_remarks']);
        });
    }
};
