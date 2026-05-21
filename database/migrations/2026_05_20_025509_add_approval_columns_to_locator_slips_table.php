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
        Schema::table('locator_slips', function (Blueprint $table) {
            $table->foreignId('approved_by_chief_id')->nullable()->constrained('users');
            $table->string('approved_by_chief_name')->nullable();
            $table->timestamp('chief_approval_date')->nullable();
            $table->foreignId('approved_by_regional_director_id')->nullable()->constrained('users');
            $table->string('approved_by_regional_director_name')->nullable();
            $table->timestamp('regional_director_approval_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locator_slips', function (Blueprint $table) {
            $table->dropForeign(['approved_by_chief_id']);
            $table->dropForeign(['approved_by_regional_director_id']);
            $table->dropColumn([
                'approved_by_chief_id',
                'approved_by_chief_name',
                'chief_approval_date',
                'approved_by_regional_director_id',
                'approved_by_regional_director_name',
                'regional_director_approval_date',
            ]);
        });
    }
};
