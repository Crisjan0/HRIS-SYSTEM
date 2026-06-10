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
        Schema::create('locator_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date_covered');
            $table->text('purpose');
            $table->time('time_from');
            $table->time('time_to');
            $table->string('destination')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->default('pending');

            // Chief Approval
            $table->foreignId('chief_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('chief_approval_status')->default('pending');
            $table->foreignId('approved_by_chief_id')->nullable()->constrained('users');
            $table->string('approved_by_chief_name')->nullable();
            $table->timestamp('chief_approval_date')->nullable();

            // Regional Director Approval
            $table->foreignId('regional_director_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('regional_director_approval_status')->default('pending');
            $table->foreignId('approved_by_regional_director_id')->nullable()->constrained('users');
            $table->string('approved_by_regional_director_name')->nullable();
            $table->timestamp('regional_director_approval_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locator_slips');
    }
};
