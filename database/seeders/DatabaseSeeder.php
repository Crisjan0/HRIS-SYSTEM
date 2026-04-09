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
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'HR Staff User',
            'email' => 'hrstaff@example.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'Director User',
            'email' => 'director@example.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name' => 'Chief User',
            'email' => 'chief@example.com',
            'password' => 'password',
        ]);

        $this->call(EmployeeSeeder::class);
        $this->call(HolidaySeeder::class);
    }
}
