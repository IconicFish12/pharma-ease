<?php

use App\Models\Medicine;
use App\Models\MedicineOrder;
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
        Schema::create('order_details', function (Blueprint $table) {
            $table->foreignUuid('order_id')->constrained('medicine_orders', 'order_id')->cascadeOnDelete();
            $table->foreignUuid('medicine_id')->constrained('medicines', 'medicine_id')->cascadeOnDelete();
            $table->primary(['order_id', 'medicine_id']);
            $table->integer('quantity');
            $table->double('unit_price');
            $table->double('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
