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
        // 1. Personal Information (1:1 with Employee)
        Schema::create('pds_personal_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->string('civil_status')->nullable();
            $table->string('height_m')->nullable();
            $table->string('weight_kg')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('gsis_id_no')->nullable();
            $table->string('pagibig_id_no')->nullable();
            $table->string('philhealth_no')->nullable();
            $table->string('sss_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('agency_employee_no')->nullable();
            $table->string('citizenship')->nullable(); // Filipino, Dual Citizenship
            $table->string('citizenship_type')->nullable(); // By Birth, By Naturalization
            $table->string('country')->nullable();
            
            // Residential Address
            $table->string('res_house_no')->nullable();
            $table->string('res_street')->nullable();
            $table->string('res_subdivision')->nullable();
            $table->string('res_barangay')->nullable();
            $table->string('res_city')->nullable();
            $table->string('res_province')->nullable();
            $table->string('res_zip_code')->nullable();

            // Permanent Address
            $table->string('perm_house_no')->nullable();
            $table->string('perm_street')->nullable();
            $table->string('perm_subdivision')->nullable();
            $table->string('perm_barangay')->nullable();
            $table->string('perm_city')->nullable();
            $table->string('perm_province')->nullable();
            $table->string('perm_zip_code')->nullable();

            $table->string('telephone_no')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email_address')->nullable();
            $table->timestamps();
        });

        // 2. Family Background (1:1 with Employee)
        Schema::create('pds_family_background', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('spouse_surname')->nullable();
            $table->string('spouse_firstname')->nullable();
            $table->string('spouse_middlename')->nullable();
            $table->string('spouse_extension')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('spouse_employer')->nullable();
            $table->string('spouse_business_address')->nullable();
            $table->string('spouse_telephone_no')->nullable();
            $table->string('father_surname')->nullable();
            $table->string('father_firstname')->nullable();
            $table->string('father_middlename')->nullable();
            $table->string('father_extension')->nullable();
            $table->string('mother_maiden_surname')->nullable();
            $table->string('mother_firstname')->nullable();
            $table->string('mother_middlename')->nullable();
            $table->timestamps();
        });

        // 3. Children (1:N)
        Schema::create('pds_children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('fullname');
            $table->date('date_of_birth');
            $table->timestamps();
        });

        // 4. Educational Background (1:N)
        Schema::create('pds_educational_background', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('level'); // Elementary, Secondary, etc.
            $table->string('school_name');
            $table->string('course')->nullable();
            $table->string('period_from')->nullable();
            $table->string('period_to')->nullable();
            $table->string('highest_level')->nullable();
            $table->string('year_graduated')->nullable();
            $table->string('honors')->nullable();
            $table->timestamps();
        });

        // 5. Civil Service Eligibility (1:N)
        Schema::create('pds_eligibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('rating')->nullable();
            $table->date('date_of_exam')->nullable();
            $table->string('place_of_exam')->nullable();
            $table->string('license_number')->nullable();
            $table->date('license_validity')->nullable();
            $table->timestamps();
        });

        // 6. Work Experience (1:N)
        Schema::create('pds_work_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable(); // Can be NULL for "Present"
            $table->string('position_title');
            $table->string('company');
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->string('salary_grade')->nullable();
            $table->string('appointment_status')->nullable();
            $table->boolean('is_gov_service')->default(false);
            $table->timestamps();
        });

        // 7. Voluntary Work (1:N)
        Schema::create('pds_voluntary_works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('organization_name');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->integer('number_of_hours')->nullable();
            $table->string('position')->nullable();
            $table->timestamps();
        });

        // 8. Learning & Development (1:N)
        Schema::create('pds_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->integer('number_of_hours')->nullable();
            $table->string('type')->nullable(); // Managerial, Technical, etc.
            $table->string('conducted_by')->nullable();
            $table->timestamps();
        });

        // 9. Other Information (1:N)
        Schema::create('pds_others', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['Skill', 'Distinction', 'Membership']);
            $table->string('description');
            $table->timestamps();
        });

        // 10. Questionnaire (1:1)
        Schema::create('pds_questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->boolean('q34_a')->default(false);
            $table->boolean('q34_b')->default(false);
            $table->text('q34_details')->nullable();
            $table->boolean('q35_a')->default(false);
            $table->boolean('q35_b')->default(false);
            $table->text('q35_details')->nullable();
            $table->date('q35_date_filed')->nullable();
            $table->string('q35_status')->nullable();
            $table->boolean('q36')->default(false);
            $table->text('q36_details')->nullable();
            $table->boolean('q37')->default(false);
            $table->text('q37_details')->nullable();
            $table->boolean('q38_a')->default(false);
            $table->text('q38_a_details')->nullable();
            $table->boolean('q38_b')->default(false);
            $table->text('q38_b_details')->nullable();
            $table->boolean('q39')->default(false);
            $table->text('q39_details')->nullable();
            $table->boolean('q40_a')->default(false);
            $table->text('q40_a_details')->nullable();
            $table->boolean('q40_b')->default(false);
            $table->text('q40_b_details')->nullable();
            $table->boolean('q40_c')->default(false);
            $table->text('q40_c_details')->nullable();
            $table->timestamps();
        });

        // 11. References (1:N)
        Schema::create('pds_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('address');
            $table->string('telephone_no');
            $table->timestamps();
        });

        // 12. Government Issued ID (1:1)
        Schema::create('pds_government_ids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('id_type');
            $table->string('id_no');
            $table->string('date_place_issuance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pds_government_ids');
        Schema::dropIfExists('pds_references');
        Schema::dropIfExists('pds_questionnaires');
        Schema::dropIfExists('pds_others');
        Schema::dropIfExists('pds_trainings');
        Schema::dropIfExists('pds_voluntary_works');
        Schema::dropIfExists('pds_work_experiences');
        Schema::dropIfExists('pds_eligibilities');
        Schema::dropIfExists('pds_educational_background');
        Schema::dropIfExists('pds_children');
        Schema::dropIfExists('pds_family_background');
        Schema::dropIfExists('pds_personal_information');
    }
};
