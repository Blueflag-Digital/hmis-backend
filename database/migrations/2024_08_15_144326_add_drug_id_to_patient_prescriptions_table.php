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
        Schema::table('patient_prescriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('drug_id')->after('number_dispensed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_prescriptions', function (Blueprint $table) {
            $table->dropColumn('drug_id');
        });
    }
};
