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
                'lastname' => 'Admin',
                'firstname' => 'User',
                'middlename' => 'A',
                'role' => 'admin',
            ],
            [
                'email' => 'hrstaff@example.com',
                'lastname' => 'HR',
                'firstname' => 'Staff',
                'middlename' => 'S',
                'role' => 'hrstaff',
            ],
            [
                'email' => 'director@example.com',
                'lastname' => 'Director',
                'firstname' => 'User',
                'middlename' => 'D',
                'role' => 'director',
            ],
            [
                'email' => 'employee@example.com',
                'lastname' => 'Employee',
                'firstname' => 'User',
                'middlename' => 'E',
                'role' => 'employee',
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
