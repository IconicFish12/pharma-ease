<?php

namespace App\Models;

use App\Http\Resources\MedicineCategoryResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseResourceCollection(MedicineCategoryResource::class)]
class MedicineCategory extends Model
{
    /** @use HasFactory<\Database\Factories\MedicineCategoryFactory> */
    use HasUuids;
    use HasFactory;
    use \App\Traits\Auditable;

    public function getCustomModuleName() {
        return 'Inventory';
    }

    protected $table = 'medicine_categories';

    protected $primaryKey = 'category_id';

    protected $guarded = ['category_id'];

    public $incrementing = false;

    public function medicine() {
        return $this->hasMany(Medicine::class);
    }
}
