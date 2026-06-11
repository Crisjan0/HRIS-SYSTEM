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
        Schema::create('cto_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // earn, use
            $table->date('date_start');
            $table->date('date_end');
            $table->decimal('hours', 5, 2);
            $table->text('purpose');
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected

            $table->foreignId('approved_by_chief')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('chief_status')->default('pending');
            $table->text('chief_remarks')->nullable();

            $table->foreignId('approved_by_hrstaff')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('hrstaff_status')->default('pending');
            $table->text('hrstaff_remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cto_requests');
    }
};
