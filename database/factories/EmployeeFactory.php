<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lastname' => $this->faker->lastName(),
            'firstname' => $this->faker->firstName(),
            'middlename' => $this->faker->lastName(),
            'suffix' => $this->faker->optional(0.2)->randomElement(['Jr.', 'Sr.', 'II', 'III']),
            'division' => $this->faker->randomElement([
                'Finance and Administrative Division',
                'Migrant Workers Processing Division',
                'Migrant Workers Protection Division',
                'Welfare and Reintegration Division',
            ]),
            'role' => $this->faker->randomElement(['EMPLOYEE', 'HRSTAFF', 'CHIEF', 'REGIONALDIRECTOR']),
            'user_id' => User::factory(),
        ];
    }
}
