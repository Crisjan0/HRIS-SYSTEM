<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('locator_slips', 'chief_remarks')) {
            Schema::table('locator_slips', function (Blueprint $table) {
                $table->text('chief_remarks')->nullable()->after('chief_approval_date');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('locator_slips', 'chief_remarks')) {
            Schema::table('locator_slips', function (Blueprint $table) {
                $table->dropColumn('chief_remarks');
            });
        }
    }
};
