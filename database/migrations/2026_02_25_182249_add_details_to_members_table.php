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
        Schema::table('members', function (Blueprint $table) {
            // Personal Information
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('dob')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();

            // Contact Information
            $table->string('physical_address')->nullable();
            $table->string('town')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('county')->nullable();
            $table->string('sub_county')->nullable();
            $table->string('village')->nullable();
            $table->string('nearest_market')->nullable();
            $table->string('mobile_no')->nullable();

            // Next of Kin
            $table->string('kin_name')->nullable();
            $table->string('kin_mobile')->nullable();
            $table->string('kin_village')->nullable();
            $table->string('kin_county')->nullable();
            $table->string('kin_town')->nullable();
            $table->string('kin_sub_location')->nullable();
            $table->string('kin_sub_county')->nullable();
            $table->date('kin_dob')->nullable();

            // Business Information
            $table->string('business_name')->nullable();
            $table->string('business_address')->nullable();
            $table->string('business_county')->nullable();
            $table->string('business_town')->nullable();
            $table->string('business_sub_county')->nullable();
            $table->string('business_postal_code')->nullable();

            // Guarantors
            $table->string('guarantor1_name')->nullable();
            $table->string('guarantor1_mobile')->nullable();
            $table->string('guarantor1_relationship')->nullable();
            $table->string('guarantor2_name')->nullable();
            $table->string('guarantor2_mobile')->nullable();
            $table->string('guarantor2_relationship')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'middle_name', 'last_name', 'gender', 'dob', 'marital_status',
                'physical_address', 'town', 'postal_code', 'county', 'sub_county', 'village',
                'nearest_market', 'mobile_no', 'kin_name', 'kin_mobile', 'kin_village',
                'kin_county', 'kin_town', 'kin_sub_location', 'kin_sub_county', 'kin_dob',
                'business_name', 'business_address', 'business_county', 'business_town',
                'business_sub_county', 'business_postal_code', 'guarantor1_name',
                'guarantor1_mobile', 'guarantor1_relationship', 'guarantor2_name',
                'guarantor2_mobile', 'guarantor2_relationship',
            ]);
        });
    }
};
