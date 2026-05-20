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
            $table->dropForeign(['recommending_approval_id']);
            $table->dropForeign(['approved_by_id']);
            $table->dropColumn(['recommending_approval_id', 'approved_by_id']);

            $table->foreignId('chief_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('chief_approval_status')->default('pending');
            $table->foreignId('regional_director_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('regional_director_approval_status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locator_slips', function (Blueprint $table) {
            $table->dropColumn(['chief_id', 'chief_approval_status', 'regional_director_id', 'regional_director_approval_status']);
            $table->foreignId('recommending_approval_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }
};
