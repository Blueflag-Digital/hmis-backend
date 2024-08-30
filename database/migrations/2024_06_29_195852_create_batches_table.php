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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained();
            $table->integer('quantity_received')->nullable();
            $table->integer('quantity_available')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained();
            $table->string('lpo')->nullable();
            $table->decimal('buying_price', 8, 2)->nullable();
            $table->decimal('selling_price', 8, 2)->nullable();
            $table->foreignId('pack_size_id')->constrained()->change();
            $table->foreignId('unit_of_measure_id')->constrained()->change();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
