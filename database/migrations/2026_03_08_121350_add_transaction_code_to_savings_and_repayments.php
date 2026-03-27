<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add transaction_code to savings
        Schema::table('savings', function (Blueprint $table) {
            $table->string('transaction_code')->nullable()->after('notes');
        });

        // Add transaction_code to repayments
        Schema::table('repayments', function (Blueprint $table) {
            $table->string('transaction_code')->nullable()->after('reference_number');
        });

        // Create a table to track used transaction codes (prevents reuse across all payments)
        Schema::create('used_transaction_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('session_ref')->nullable();
            $table->timestamp('used_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('savings', function (Blueprint $table) {
            $table->dropColumn('transaction_code');
        });
        Schema::table('repayments', function (Blueprint $table) {
            $table->dropColumn('transaction_code');
        });
        Schema::dropIfExists('used_transaction_codes');
    }
};
