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
        Schema::table('cto_requests', function (Blueprint $table) {
            $table->dropForeign(['approved_by_regionaldirector']);
            $table->dropColumn(['approved_by_regionaldirector', 'rd_status', 'rd_remarks']);

            $table->foreignId('approved_by_hrstaff')->nullable()->after('chief_remarks')->constrained('employees')->nullOnDelete();
            $table->string('hrstaff_status')->default('pending')->after('approved_by_hrstaff');
            $table->text('hrstaff_remarks')->nullable()->after('hrstaff_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cto_requests', function (Blueprint $table) {
            $table->dropForeign(['approved_by_hrstaff']);
            $table->dropColumn(['approved_by_hrstaff', 'hrstaff_status', 'hrstaff_remarks']);

            $table->foreignId('approved_by_regionaldirector')->nullable()->after('chief_remarks')->constrained('employees')->nullOnDelete();
            $table->string('rd_status')->default('pending')->after('approved_by_regionaldirector');
            $table->text('rd_remarks')->nullable()->after('rd_status');
        });
    }
};
