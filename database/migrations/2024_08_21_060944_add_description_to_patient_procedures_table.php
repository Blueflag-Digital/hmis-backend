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
        Schema::table('patient_procedures', function (Blueprint $table) {
            $table->longText('description')->after('consultation_id')->nullable();
            $table->unsignedBigInteger('hospital_id')->after('description')->nullable();
            $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_procedures', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->dropColumn(['hospital_id','description']);
        });
    }
};
