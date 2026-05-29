<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\PdsPersonalInformation;
use App\Models\Saln;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SalnPdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Create PDS Personal Information
            PdsPersonalInformation::firstOrCreate(
                ['employee_id' => $employee->id],
                [
                    'surname' => $employee->lastname,
                    'firstname' => $employee->firstname,
                    'middlename' => $employee->middlename,
                    'name_extension' => null,
                    'date_of_birth' => $faker->dateTimeBetween('-50 years', '-20 years')->format('Y-m-d'),
                    'place_of_birth' => $faker->city,
                    'sex' => $faker->randomElement(['Male', 'Female']),
                    'civil_status' => $faker->randomElement(['Single', 'Married', 'Widowed', 'Separated']),
                    'height_m' => $faker->randomFloat(2, 1.5, 2.0),
                    'weight_kg' => $faker->randomFloat(2, 50, 100),
                    'blood_type' => $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
                    'gsis_id_no' => $faker->numerify('##-#######-#'),
                    'pagibig_id_no' => $faker->numerify('####-####-####'),
                    'philhealth_no' => $faker->numerify('##-#########-#'),
                    'sss_no' => $faker->numerify('##-#######-#'),
                    'tin_no' => $faker->numerify('###-###-###'),
                    'agency_employee_no' => $faker->numerify('EMP-####'),
                    'citizenship' => 'Filipino',
                    'citizenship_type' => 'By Birth',
                    'res_house_no' => $faker->buildingNumber,
                    'res_street' => $faker->streetName,
                    'res_subdivision' => $faker->streetSuffix,
                    'res_barangay' => 'Barangay ' . $faker->numberBetween(1, 100),
                    'res_city' => $faker->city,
                    'res_province' => $faker->state,
                    'res_zip_code' => $faker->postcode,
                    'telephone_no' => $faker->phoneNumber,
                    'mobile_no' => $employee->contact_number ?? $faker->phoneNumber,
                    'email_address' => $employee->user ? $employee->user->email : $faker->unique()->safeEmail,
                ]
            );

            // Create SALN
            $assets = $faker->randomFloat(2, 100000, 5000000);
            $liabilities = $faker->randomFloat(2, 10000, 1000000);
            Saln::firstOrCreate(
                ['employee_id' => $employee->id],
                [
                    'type_of_filing' => 'Joint Filing',
                    'as_of_date' => now()->startOfYear(),
                    'declarant_info' => [
                        'name' => $employee->lastname . ', ' . $employee->firstname,
                        'address' => $faker->address,
                        'position' => $employee->role,
                        'agency' => 'Department of Migrant Workers',
                        'office_address' => 'Manila, Philippines',
                    ],
                    'spouse_info' => [
                        'name' => 'N/A',
                        'address' => 'N/A',
                        'position' => 'N/A',
                        'agency' => 'N/A',
                        'office_address' => 'N/A',
                    ],
                    'filing_status' => 'Joint',
                    'children' => [],
                    'real_properties' => [
                        [
                            'description' => 'House and Lot',
                            'kind' => 'Residential',
                            'location' => $faker->address,
                            'assessed_value' => $faker->numberBetween(50000, 500000),
                            'fair_market_value' => $faker->numberBetween(500000, 2000000),
                            'acquisition_year' => $faker->year,
                            'acquisition_mode' => 'Purchase',
                            'acquisition_cost' => $assets,
                        ]
                    ],
                    'personal_properties' => [],
                    'liabilities' => [
                        [
                            'nature' => 'Personal Loan',
                            'creditor' => 'Bank',
                            'outstanding_balance' => $liabilities,
                        ]
                    ],
                    'has_business_interests' => false,
                    'business_interests' => [],
                    'has_relatives_in_gov' => false,
                    'relatives_in_gov' => [],
                    'total_assets' => $assets,
                    'total_liabilities' => $liabilities,
                    'net_worth' => $assets - $liabilities,
                ]
            );
        }
    }
}
