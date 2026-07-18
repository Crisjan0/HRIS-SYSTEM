<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cto_requests', function (Blueprint $table) {
            $table->string('applicant_signature_path')->nullable()->after('attachment_path');
            $table->decimal('cto_balance_before', 8, 2)->nullable()->after('applicant_signature_path');
            $table->decimal('cto_balance_after', 8, 2)->nullable()->after('cto_balance_before');
        });
    }

    public function down(): void
    {
        Schema::table('cto_requests', function (Blueprint $table) {
            $table->dropColumn([
                'applicant_signature_path',
                'cto_balance_before',
                'cto_balance_after',
            ]);
        });
    }
};
