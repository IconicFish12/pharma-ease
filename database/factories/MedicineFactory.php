<?php

namespace Database\Factories;

use App\Models\MedicineCategory;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'medicine_name' => $this->faker->word(),
            'sku' => $this->faker->numerify('MED-####'),
            'description' => $this->faker->paragraphs(2, true),
            'category_id' =>  MedicineCategory::inRandomOrder()->first()->category_id ?? MedicineCategory::factory(),
            'supplier_id' => Supplier::inRandomOrder()->first()->supplier_id ?? Supplier::factory(),
            'stock' => $this->faker->randomNumber(3, false),
            'price' => $this->faker->randomNumber(5, true),
            'expired_date' => $this->faker->dateTimeBetween('+4 days', '+1 week'),
        ];
    }
}
