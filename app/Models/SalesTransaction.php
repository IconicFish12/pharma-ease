<?php

namespace App\Models;

use App\Http\Resources\SalesTransactionResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[UseResourceCollection(SalesTransactionResource::class)]
class SalesTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\SalesTransactionFactory> */
    use HasUuids;
    use HasFactory;
    use \App\Traits\Auditable;
    public function getCustomModuleName() {
        return 'Transaction Details Management';
    }

    protected $table = 'sales_transactions';

    protected $primaryKey = 'sales_id';

    protected $guarded = ['sales_id', 'kode_penjualan'];

    public $incrementing = false;

    public function user() {
        return $this->belongsTo(
            User::class,
            'user_id',
            'user_id'
        );
    }

    public function medicines(): BelongsToMany
    {
        return $this->belongsToMany(
            Medicine::class,
            'transaction_details',
            'sales_id',
            'medicine_id'
        )
        ->using(TransactionDetails::class)
        ->withPivot(['quantity', 'unit_price', 'subtotal'])
        ->withTimestamps();
    }

}
