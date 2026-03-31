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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamp('date_filed');
            $table->text('reason');
            $table->string('status')->default('pending'); // overall status: pending, approved, rejected, cancelled

            // Sequential Approval Flow
            $table->foreignId('approved_by_chief')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('chief_status')->default('pending'); // pending, approved, rejected
            $table->text('chief_remarks')->nullable();

            $table->foreignId('approved_by_hrstaff')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('hrstaff_status')->default('pending'); // pending, approved, rejected
            $table->text('hrstaff_remarks')->nullable();

            $table->foreignId('approved_by_regionaldirector')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('rd_status')->default('pending'); // pending, approved, rejected
            $table->text('rd_remarks')->nullable();

            $table->text('remarks')->nullable(); // general remarks

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
