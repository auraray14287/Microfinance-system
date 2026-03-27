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
        Schema::table('groups', function (Blueprint $table) {
            $table->string('registration_number')->nullable()->unique();
            $table->string('location')->nullable();
            $table->string('contact')->nullable();
            $table->foreignId('assigned_officer')->nullable()->constrained('users')->onDelete('set null');
            $table->string('chairperson')->nullable();
            $table->string('secretary')->nullable();
            $table->string('treasurer')->nullable();
            $table->integer('minimum_members')->default(10);
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn([
                'registration_number',
                'location',
                'contact',
                'assigned_officer',
                'chairperson',
                'secretary',
                'treasurer',
                'minimum_members',
            ]);
        });
    }
};
