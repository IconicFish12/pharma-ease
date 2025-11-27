<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medicine extends Model
{
    /** @use HasFactory<\Database\Factories\MedicineFactory> */
    use HasUuids;
    use HasFactory;

    protected $table = 'medicines';

    protected $primaryKey = 'medicine_id';

    protected $guarded = ['medicine_id', ];

    public $incrementing = false;

    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'category_id', 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function orders()
    {
        return $this->belongsToMany(MedicineOrder::class, 'order_details', 'medicine_id', 'order_id')
                    ->using(OrderDetails::class)
                    ->withPivot(['quantity', 'unit_price', 'subtotal'])
                    ->withTimestamps();
    }

    public function salesTransactions(): BelongsToMany
    {
        return $this->belongsToMany(
            SalesTransaction::class,
            'transaction_details',
            'medicine_id',
            'sales_id'
        )
        ->using(TransactionDetails::class)
        ->withPivot(['quantity', 'unit_price', 'subtotal'])
        ->withTimestamps();
    }
}
