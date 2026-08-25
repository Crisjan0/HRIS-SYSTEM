<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('employees')
            ->where('division', 'Migrant Workers Protections Division')
            ->update(['division' => 'Migrant Workers Protection Division']);

        DB::table('employees')
            ->where('division', 'Welfare and Reintegration Division')
            ->update(['division' => 'Welfare and Reintegration Services Division']);
    }

    public function down(): void
    {
        DB::table('employees')
            ->where('division', 'Migrant Workers Protection Division')
            ->update(['division' => 'Migrant Workers Protections Division']);

        DB::table('employees')
            ->where('division', 'Welfare and Reintegration Services Division')
            ->update(['division' => 'Welfare and Reintegration Division']);
    }
};
