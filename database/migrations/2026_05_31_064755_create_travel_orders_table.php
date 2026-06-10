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
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('travel_type'); // local, foreign, official_business
            $table->date('travel_date_start');
            $table->date('travel_date_end');
            $table->string('places_of_travel');
            $table->text('purpose');
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected

            // Chief approval (Level 1)
            $table->foreignId('approved_by_chief')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('chief_status')->default('pending');
            $table->text('chief_remarks')->nullable();

            // Regional Director approval (Level 2)
            $table->foreignId('approved_by_regionaldirector')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('rd_status')->default('pending');
            $table->text('rd_remarks')->nullable();

            $table->timestamps();
        });

        // Pivot table for companion employees (optional)
        Schema::create('travel_order_companions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_order_companions');
        Schema::dropIfExists('travel_orders');
    }
};
