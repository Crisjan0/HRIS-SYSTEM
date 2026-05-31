<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employeeData = [
            [
                'email' => 'admin@example.com',
                'lastname' => 'Dela Cruz',
                'firstname' => 'Juan',
                'middlename' => 'S',
                'position' => 'admin',
                'user_id' => 1,
                
            ],
            [
                'email' => 'hrstaff@example.com',
                'lastname' => 'Reyes',
                'firstname' => 'Maria',
                'middlename' => 'B',
                'position' => 'hrstaff',
                'user_id' => 2,
            ],
            [
                'email' => 'director@example.com',
                'lastname' => 'Mendoza',
                'firstname' => 'Pedro',
                'middlename' => 'G',
                'position' => 'regionaldirector',
                'user_id' => 4,
            ],
            [
                'email' => 'employee@example.com',
                'lastname' => 'Garcia',
                'firstname' => 'Anna',
                'middlename' => 'V',
                'position' => 'employee',
                'user_id' => 5,
            ],
            [
                'email' => 'chief@example.com',
                'lastname' => 'Aquino',
                'firstname' => 'Miguel',
                'middlename' => 'T',
                'position' => 'chief',
                'user_id' => 6,
            ],
        ];

        foreach ($employeeData as $data) {
            $user = User::where('email', $data['email'])->first();

            if ($user) {
                // Create employee record
                Employee::create([
                    'lastname' => $data['lastname'],
                    'firstname' => $data['firstname'],
                    'middlename' => $data['middlename'],
                    'position' => $data['position'],
                    'account_role' => $data['position'], // Assuming account_role is the same as position for seeding
                    'user_id' => $data['user_id'],
                ]);
            }
        }
    }
}
