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
        Schema::create('salns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('type_of_filing'); // assumption, annual, exit
            $table->date('as_of_date');
            
            // Personal Info
            $table->json('declarant_info');
            $table->json('spouse_info')->nullable();
            $table->string('filing_status'); // joint, separate, not_applicable
            
            // Children
            $table->json('children')->nullable();
            
            // Assets & Liabilities
            $table->json('real_properties')->nullable();
            $table->json('personal_properties')->nullable();
            $table->json('liabilities')->nullable();
            
            // Financial Connections & Relatives
            $table->boolean('has_business_interests')->default(false);
            $table->json('business_interests')->nullable();
            
            $table->boolean('has_relatives_in_gov')->default(false);
            $table->json('relatives_in_gov')->nullable();
            
            // Totals
            $table->decimal('total_assets', 15, 2)->default(0);
            $table->decimal('total_liabilities', 15, 2)->default(0);
            $table->decimal('net_worth', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salns');
    }
};
