<?php
// 2024_11_21_160300_create_billing_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('billing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_visit_id')->constrained()->onDelete('cascade');
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->morphs('billable');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, paid
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('billing_items');
    }
};
