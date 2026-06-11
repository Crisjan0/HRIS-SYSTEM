<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@dmw.gov.ph',
            'password' => 'password',
            'is_approved' => true,
        ]);

        User::factory()->create([
            'name' => 'HR Staff User',
            'email' => 'hrstaff@dmw.gov.ph',
            'password' => 'password',
            'is_approved' => true,
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@dmw.gov.ph',
            'password' => 'password',
            'is_approved' => true,
        ]);

        User::factory()->create([
            'name' => 'Director User',
            'email' => 'director@dmw.gov.ph',
            'password' => 'password',
            'is_approved' => true,
        ]);

        User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@dmw.gov.ph',
            'password' => 'password',
            'is_approved' => true,
        ]);

        User::factory()->create([
            'name' => 'Chief User',
            'email' => 'chief@dmw.gov.ph',
            'password' => 'password',
            'is_approved' => true,
        ]);

        $this->call(EmployeeSeeder::class);
        $this->call(HolidaySeeder::class);
        $this->call(LeaveTypeSeeder::class);
        $this->call(PdsSeeder::class);
        $this->call(SalnSeeder::class);
    }
}
