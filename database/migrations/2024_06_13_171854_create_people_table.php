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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id', 255)->on('users')->onDelete('cascade')->nullable();
            $table->foreignId('person_type_id')->on('person_types')->onDelete('cascade');
            $table->foreignId('city_id')->on('cities')->onDelete('cascade');
            $table->string('first_name', 100)->index();
            $table->string('last_name', 100)->index();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('phone', 20)->unique()->nullable();
            $table->string('identifier', 20)->nullable()->default('national_id');
            $table->string('identifier_number', 20)->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
