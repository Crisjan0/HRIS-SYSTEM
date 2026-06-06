<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class SalnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $faker = \Faker\Factory::create('en_PH');

        foreach ($employees as $employee) {
            $realPropCost = $faker->numberBetween(500000, 3000000);
            $personalPropCost = $faker->numberBetween(200000, 1000000);
            $liabilityAmount = $faker->numberBetween(50000, 500000);
            
            $totalAssets = $realPropCost + $personalPropCost;
            $netWorth = $totalAssets - $liabilityAmount;

            $employee->salns()->updateOrCreate(
                ['employee_id' => $employee->id, 'as_of_date' => now()->startOfYear()->format('Y-m-d')],
                [
                    'type_of_filing' => $faker->randomElement(['Joint Filing', 'Separate Filing', 'Not Applicable']),
                    'declarant_info' => [
                        'family_name' => $employee->lastname,
                        'first_name' => $employee->firstname,
                        'middle_initial' => substr($employee->middlename ?? '', 0, 1) ?? '',
                        'position' => $employee->position ?? 'N/A',
                        'agency_office' => 'HRIS',
                        'office_address' => $faker->city,
                    ],
                    'spouse_info' => [
                        'family_name' => $employee->lastname,
                        'first_name' => $faker->firstName,
                        'middle_initial' => 'A',
                        'position' => $employee->position ?? 'N/A',
                        'agency_office' => $faker->company,
                        'office_address' => $faker->city,
                    ],
                    'filing_status' => 'Joint Filing',
                    'children' => [],
                    'real_properties' => [
                        [
                            'description' => 'Residential Lot',
                            'kind' => 'Residential',
                            'exact_location' => $faker->city,
                            'assessed_value' => $realPropCost * 0.4,
                            'current_fair_market_value' => $realPropCost * 1.5,
                            'acquisition_year' => $faker->year,
                            'acquisition_mode' => 'Purchase',
                            'acquisition_cost' => $realPropCost,
                        ]
                    ],
                    'personal_properties' => [
                        [
                            'description' => 'Cash in Bank',
                            'year_acquired' => $faker->dateTimeBetween('-5 years', 'now')->format('Y'),
                            'acquisition_cost' => $personalPropCost * 0.4,
                        ],
                        [
                            'description' => 'Vehicle',
                            'year_acquired' => $faker->dateTimeBetween('-10 years', 'now')->format('Y'),
                            'acquisition_cost' => $personalPropCost * 0.6,
                        ]
                    ],
                    'liabilities' => [
                        [
                            'nature' => 'Personal Loan',
                            'name_of_creditors' => $faker->company,
                            'outstanding_balance' => $liabilityAmount,
                        ]
                    ],
                    'has_business_interests' => false,
                    'business_interests' => [],
                    'has_relatives_in_gov' => false,
                    'relatives_in_gov' => [],
                    'total_assets' => $totalAssets,
                    'total_liabilities' => $liabilityAmount,
                    'net_worth' => $netWorth,
                ]
            );
        }
    }
}
