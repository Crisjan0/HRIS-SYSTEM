<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pds_trainings', function (Blueprint $table) {
            $table->string('attachment_path')->nullable()->after('number_of_hours');
        });
    }

    public function down(): void
    {
        Schema::table('pds_trainings', function (Blueprint $table) {
            $table->dropColumn('attachment_path');
        });
    }
};
