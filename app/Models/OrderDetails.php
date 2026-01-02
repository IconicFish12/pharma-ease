<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderDetails extends Pivot
{
    /** @use HasFactory<\Database\Factories\OrderDetailsFactory> */
    use HasFactory;
    use \App\Traits\Auditable;

    public function getCustomModuleName() {
        return 'Medicine Order Details Management';
    }

    protected $table = 'order_details';

    protected $guarded = [];

    public $incrementing = false;
}
