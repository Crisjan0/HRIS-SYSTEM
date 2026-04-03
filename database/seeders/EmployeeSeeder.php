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
                'role' => 'admin',
            ],
            [
                'email' => 'hrstaff@example.com',
                'lastname' => 'Reyes',
                'firstname' => 'Maria',
                'middlename' => 'B',
                'role' => 'hrstaff',
            ],
            [
                'email' => 'director@example.com',
                'lastname' => 'Mendoza',
                'firstname' => 'Pedro',
                'middlename' => 'G',
                'role' => 'regionaldirector',
            ],
            [
                'email' => 'employee@example.com',
                'lastname' => 'Garcia',
                'firstname' => 'Anna',
                'middlename' => 'V',
                'role' => 'employee',
            ],
            [
                'email' => 'chief@example.com',
                'lastname' => 'Aquino',
                'firstname' => 'Miguel',
                'middlename' => 'T',
                'role' => 'chief',
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
                    'role' => $data['role'],
                    #'user_id' => $user->id,
                ]);
            }
        }
    }
}
