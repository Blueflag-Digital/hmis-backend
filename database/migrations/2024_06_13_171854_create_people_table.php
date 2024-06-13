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
            $table->string('user_id', 255)->unique()->nullable();
            $table->foreignId('person_type_id')->on('person_types')->onDelete('cascade');
            $table->foreignId('city_id')->on('cities')->onDelete('cascade');
            $table->string('first_name', 100)->index();
            $table->string('last_name', 100)->index();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('phone', 20)->unique()->nullable();
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
