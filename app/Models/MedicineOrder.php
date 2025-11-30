<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineOrder extends Model
{
    /** @use HasFactory<\Database\Factories\MedicineOrderFactory> */
    use HasUuids;
    use HasFactory;

    protected $table = 'medicine_orders';

    protected $primaryKey = 'order_id';

    protected $guarded = ['order_id', 'order_code'];

    public $incrementing = false;

    public function user() {
        return $this->belongsTo(
            User::class,
            'user_id',
            'user_id'
        );
    }

    public function supplier() {
        return $this->belongsToMany(
            Supplier::class,
            'supplier_id',
            'supplier_id'
        );
    }// Import model pivot tadi

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'order_details', 'order_id', 'medicine_id')
                    ->using(OrderDetails::class)
                    ->withPivot(['quantity', 'unit_price', 'subtotal'])
                    ->withTimestamps();
    }
}
