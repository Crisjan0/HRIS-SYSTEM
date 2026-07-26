<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pds_personal_information', function (Blueprint $table) {
            $table->string('res_region')->nullable()->after('res_barangay');
            $table->string('perm_region')->nullable()->after('perm_barangay');
        });
    }

    public function down(): void
    {
        Schema::table('pds_personal_information', function (Blueprint $table) {
            $table->dropColumn(['res_region', 'perm_region']);
        });
    }
};
