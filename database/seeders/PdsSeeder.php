<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class PdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $faker = \Faker\Factory::create('en_PH');

        foreach ($employees as $employee) {
            // 1. Personal Information
            $employee->pdsPersonal()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'surname' => $employee->lastname,
                    'firstname' => $employee->firstname,
                    'middlename' => $employee->middlename,
                    'date_of_birth' => now()->subYears(rand(25, 50))->format('Y-m-d'),
                    'place_of_birth' => $faker->city,
                    'sex' => $faker->randomElement(['Male', 'Female']),
                    'civil_status' => $faker->randomElement(['Single', 'Married', 'Widowed', 'Separated']),
                    'citizenship' => 'Filipino',
                    'citizenship_type' => 'By Birth',
                    'height_m' => (string) $faker->randomFloat(2, 1.5, 1.9),
                    'weight_kg' => (string) $faker->numberBetween(50, 90),
                    'blood_type' => $faker->randomElement(['A+', 'B+', 'AB+', 'O+', 'A-', 'B-', 'AB-', 'O-']),
                    'email_address' => strtolower($employee->firstname . '.' . $employee->lastname . '@example.com'),
                    'mobile_no' => $faker->mobileNumber,
                    'res_house_no' => $faker->buildingNumber,
                    'res_street' => $faker->streetName,
                    'res_barangay' => $faker->barangay ?? 'Barangay',
                    'res_city' => $faker->city,
                    'res_province' => $faker->province ?? 'Province',
                    'res_zip_code' => $faker->postcode,
                ]
            );

            // 2. Family Background
            $employee->pdsFamily()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'father_surname' => $employee->lastname,
                    'father_firstname' => $faker->firstNameMale,
                    'mother_maiden_surname' => $faker->lastName,
                    'mother_firstname' => $faker->firstNameFemale,
                ]
            );

            // 3. Educational Background
            if ($employee->pdsEducation()->count() == 0) {
                $employee->pdsEducation()->create([
                    'level' => 'College',
                    'school_name' => $faker->company . ' University',
                    'course' => 'BS ' . $faker->jobTitle,
                    'period_from' => '2010',
                    'period_to' => '2014',
                    'year_graduated' => '2014',
                ]);
            }

            // 4. Civil Service Eligibility
            if ($employee->pdsEligibilities()->count() == 0) {
                $employee->pdsEligibilities()->create([
                    'title' => 'Civil Service Professional',
                    'rating' => (string) $faker->randomFloat(2, 80, 99),
                    'date_of_exam' => $faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
                ]);
            }

            // 5. Work Experience
            if ($employee->pdsWorkExperiences()->count() == 0) {
                $employee->pdsWorkExperiences()->create([
                    'date_from' => $faker->dateTimeBetween('-5 years', '-1 year')->format('Y-m-d'),
                    'date_to' => now()->format('Y-m-d'),
                    'position_title' => $faker->jobTitle,
                    'company' => $faker->company,
                    'monthly_salary' => $faker->numberBetween(25000, 85000),
                ]);
            }

            // 6. References
            if ($employee->pdsReferences()->count() < 3) {
                for ($i = 1; $i <= 3; $i++) {
                    $employee->pdsReferences()->create([
                        'name' => $faker->name,
                        'address' => $faker->city,
                        'telephone_no' => $faker->mobileNumber,
                    ]);
                }
            }
        }
    }
}
