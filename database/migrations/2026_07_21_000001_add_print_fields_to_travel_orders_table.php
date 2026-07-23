<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->string('requesting_office')->nullable()->after('purpose');
            $table->text('notes_remarks')->nullable()->after('requesting_office');
            $table->string('driver_name')->nullable()->after('notes_remarks');
            $table->string('vehicle_plate_no')->nullable()->after('driver_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->dropColumn([
                'requesting_office',
                'notes_remarks',
                'driver_name',
                'vehicle_plate_no',
            ]);
        });
    }
};
