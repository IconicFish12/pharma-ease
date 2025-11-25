<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineCategory extends Model
{
    /** @use HasFactory<\Database\Factories\MedicineCategoryFactory> */
    use HasUuids;
    use HasFactory;

    protected $table = 'medicine_categories';

    protected $primaryKey = 'category_id';

    protected $guarded = ['category_id'];

    public $incrementing = false;

    public function medicine() {
        return $this->hasMany(Medicine::class);
    }
}
