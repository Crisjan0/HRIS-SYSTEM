<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $year = now()->year;

        // Fixed Date Holidays
        $fixedHolidays = [
            ['name' => "New Year's Day", 'month' => 1, 'day' => 1],
            ['name' => 'People Power Anniversary', 'month' => 2, 'day' => 25],
            ['name' => 'Araw ng Kagitingan', 'month' => 4, 'day' => 9],
            ['name' => 'Labor Day', 'month' => 5, 'day' => 1],
            ['name' => 'Independence Day', 'month' => 6, 'day' => 12],
            ['name' => 'All Saints\' Day', 'month' => 11, 'day' => 1],
            ['name' => 'All Souls\' Day', 'month' => 11, 'day' => 2],
            ['name' => 'Bonifacio Day', 'month' => 11, 'day' => 30],
            ['name' => 'Feast of the Immaculate Conception', 'month' => 12, 'day' => 8],
            ['name' => 'Christmas Day', 'month' => 12, 'day' => 25],
            ['name' => 'Rizal Day', 'month' => 12, 'day' => 30],
            ['name' => 'Last Day of the Year', 'month' => 12, 'day' => 31],
        ];

        // Seed fixed holidays for current and next year
        foreach ([$year, $year + 1] as $y) {
            foreach ($fixedHolidays as $h) {
                \App\Models\Holiday::updateOrCreate(
                    ['date' => sprintf('%04d-%02d-%02d', $y, $h['month'], $h['day'])],
                    ['name' => $h['name']]
                );
            }
        }

        // Movable Holidays (Manual entries for 2026 for now)
        // These can be updated per year in the Manage Holidays UI
        $movable2026 = [
            ['name' => 'Chinese New Year', 'date' => '2026-01-29'],
            ['name' => 'Maundy Thursday', 'date' => '2026-04-02'],
            ['name' => 'Good Friday', 'date' => '2026-04-03'],
            ['name' => 'Black Saturday', 'date' => '2026-04-04'],
            ['name' => 'Eid al-Fitr', 'date' => '2026-03-20'],
            ['name' => 'Eid al-Adha', 'date' => '2026-05-27'],
            ['name' => 'National Heroes Day', 'date' => '2026-08-31'],
        ];

        if ($year == 2026) {
            foreach ($movable2026 as $m) {
                \App\Models\Holiday::updateOrCreate(
                    ['date' => $m['date']],
                    ['name' => $m['name']]
                );
            }
        }
    }
}
