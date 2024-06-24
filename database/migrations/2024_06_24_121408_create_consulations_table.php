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
        Schema::create('consulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_visit_id')->on('patient_visits')->onDelete('cascade');
            $table->decimal('height_cm', 3,1)->nullable();
            $table->decimal('weight_kg', 3,1)->nullable();
            $table->text('allergies')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('past_medical_history')->nullable();
            $table->text('family_medical_history')->nullable();
            $table->text('immunization_history')->nullable();

            $table->text('reason_for_visit')->nullable();
            $table->string('blood_pressure', 10)->nullable();
            $table->string('heart_rate', 10)->nullable();
            $table->string('temperature', 10)->nullable();
            $table->string('respiratory_rate', 10)->nullable();
            $table->string('oxygen_saturation', 10)->nullable();

            $table->text('doctors_notes')->nullable();

            $table->json('diagnosis_ids')->nullable(); // To store multiple diagnosis IDs
            $table->string('custom_diagnosis')->nullable(); // To store a custom diagnosis

            $table->date('next_appointment');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulations');
    }
};
