<?php

use App\Models\MedicineCategory;
use App\Models\Supplier;
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
        Schema::create('medicines', function (Blueprint $table) {
            $table->uuid('medicine_id')->primary(true);
            $table->string('medicine_name');
            $table->string('sku')->unique()->comment('Stock Keeping Unit'); // Kode unik untuk obat
            $table->text('description')->nullable();
            $table->foreignUuid('category_id')->constrained('medicine_categories', 'category_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('supplier_id')->constrained('suppliers', 'supplier_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('stock');
            $table->decimal('price', 15, 2);
            $table->date('expired_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
