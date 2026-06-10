<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('suffix')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('division')->nullable();
            $table->string('position')->nullable();
            $table->string('account_role')->nullable();
            $table->string('rfid_number')->nullable()->unique();
            $table->text('remarks')->nullable();
            $table->string('profile_picture')->nullable();
            $table->decimal('cto_balance', 8, 2)->default(0);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
