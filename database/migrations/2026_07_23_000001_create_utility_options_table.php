<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utility_options', function (Blueprint $table) {
            $table->id();
            $table->string('group_key');
            $table->string('label');
            $table->string('value');
            $table->string('parent_group')->nullable();
            $table->string('parent_value')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['group_key', 'value', 'parent_value'], 'utility_options_unique_value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utility_options');
    }
};
