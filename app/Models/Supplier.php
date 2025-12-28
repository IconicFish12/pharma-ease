<?php

namespace App\Models;

use App\Http\Resources\SupplierResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseResourceCollection(SupplierResource::class)]
class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasUuids;
    use HasFactory;
    use \App\Traits\Auditable;

    public function getCustomModuleName() {
        return 'Supplier Management';
    }

    protected $table = 'suppliers';

    protected $primaryKey = 'supplier_id';

    protected $guarded = ['supplier_id', 'kode_penjualan'];

    public $incrementing = false;

    public function medicine() {
        return $this->hasMany(Medicine::class);
    }
}
