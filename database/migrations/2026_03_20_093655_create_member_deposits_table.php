<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['credit', 'debit']);
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->string('notes')->nullable();
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_deposits');
    }
};
