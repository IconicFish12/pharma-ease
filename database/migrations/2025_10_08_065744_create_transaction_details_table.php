<?php

use App\Models\SalesTransaction;
use App\Models\User;
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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->foreignUuid('sales_id')->constrained('sales_transactions', 'sales_id')->cascadeOnDelete();
            $table->foreignUuid('medicine_id')->constrained('medicines', 'medicine_id')->cascadeOnDelete();
            $table->primary(['sales_id', 'medicine_id']);
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
        Schema::dropIfExists('transaction_details');
    }
};
