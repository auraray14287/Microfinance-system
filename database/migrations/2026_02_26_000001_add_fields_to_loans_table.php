<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'next_payment_date')) {
                $table->date('next_payment_date')->nullable();
            }
            if (!Schema::hasColumn('loans', 'amount_per_installment')) {
                $table->decimal('amount_per_installment', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('loans', 'clearance_date')) {
                $table->date('clearance_date')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(array_filter(
                ['next_payment_date', 'amount_per_installment', 'clearance_date'],
                fn ($col) => Schema::hasColumn('loans', $col)
            ));
        });
    }
};
