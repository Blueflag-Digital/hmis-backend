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
        Schema::table('unit_of_measures', function (Blueprint $table) {
            $table->unsignedBigInteger('hospital_id')->after('name')->nullable();
            $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_of_measures', function (Blueprint $table) {
             $table->dropForeign(['hospital_id']);
            $table->dropColumn('hospital_id');
        });
    }
};
