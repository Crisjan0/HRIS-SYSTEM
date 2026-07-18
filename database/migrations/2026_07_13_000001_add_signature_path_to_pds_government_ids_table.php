<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pds_government_ids', function (Blueprint $table) {
            $table->string('signature_path')->nullable()->after('date_place_issuance');
        });
    }

    public function down(): void
    {
        Schema::table('pds_government_ids', function (Blueprint $table) {
            $table->dropColumn('signature_path');
        });
    }
};
