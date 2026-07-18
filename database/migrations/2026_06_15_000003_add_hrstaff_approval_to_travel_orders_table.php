<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('travel_orders', 'approved_by_hrstaff')) {
                $table->foreignId('approved_by_hrstaff')->nullable()->after('chief_remarks')->constrained('employees')->nullOnDelete();
            }

            if (! Schema::hasColumn('travel_orders', 'hrstaff_status')) {
                $table->string('hrstaff_status')->default('pending')->after('approved_by_hrstaff');
            }

            if (! Schema::hasColumn('travel_orders', 'hrstaff_remarks')) {
                $table->text('hrstaff_remarks')->nullable()->after('hrstaff_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            if (Schema::hasColumn('travel_orders', 'approved_by_hrstaff')) {
                $table->dropConstrainedForeignId('approved_by_hrstaff');
            }

            $columns = array_filter([
                Schema::hasColumn('travel_orders', 'hrstaff_status') ? 'hrstaff_status' : null,
                Schema::hasColumn('travel_orders', 'hrstaff_remarks') ? 'hrstaff_remarks' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
