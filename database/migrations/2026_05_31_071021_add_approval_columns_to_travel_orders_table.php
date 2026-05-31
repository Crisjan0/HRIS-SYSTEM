<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            // Chief approval (Level 1)
            $table->foreignId('approved_by_chief')->nullable()->constrained('employees')->onDelete('set null')->after('status');
            $table->string('chief_status')->default('pending')->after('approved_by_chief');
            $table->text('chief_remarks')->nullable()->after('chief_status');

            // Regional Director approval (Level 2)
            $table->foreignId('approved_by_regionaldirector')->nullable()->constrained('employees')->onDelete('set null')->after('chief_remarks');
            $table->string('rd_status')->default('pending')->after('approved_by_regionaldirector');
            $table->text('rd_remarks')->nullable()->after('rd_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->dropForeign(['approved_by_chief']);
            $table->dropForeign(['approved_by_regionaldirector']);
            $table->dropColumn([
                'approved_by_chief',
                'chief_status',
                'chief_remarks',
                'approved_by_regionaldirector',
                'rd_status',
                'rd_remarks',
            ]);
        });
    }
};
