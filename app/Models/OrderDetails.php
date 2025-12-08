<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderDetails extends Pivot
{
    /** @use HasFactory<\Database\Factories\OrderDetailsFactory> */
    // use HasUuids;
    use HasFactory;

    protected $table = 'order_details';

    // protected $primaryKey = ['order_id', 'medicine_id'];

    protected $guarded = [];

    public $incrementing = false;
}
