<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locator_slips', function (Blueprint $table) {
            if (Schema::hasColumn('locator_slips', 'time_from')) {
                $table->time('time_from')->nullable()->change();
            }

            if (Schema::hasColumn('locator_slips', 'time_to')) {
                $table->time('time_to')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('locator_slips', function (Blueprint $table) {
            if (Schema::hasColumn('locator_slips', 'time_from')) {
                $table->time('time_from')->nullable(false)->change();
            }

            if (Schema::hasColumn('locator_slips', 'time_to')) {
                $table->time('time_to')->nullable(false)->change();
            }
        });
    }
};
