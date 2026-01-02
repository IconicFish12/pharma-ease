<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TransactionDetails extends Pivot
{
    /** @use HasFactory<\Database\Factories\TransactionDetailsFactory> */
    use HasFactory;
    use \App\Traits\Auditable;

    public function getCustomModuleName() {
        return 'Transaction Details Management';
    }

    protected $table = 'transaction_details';

    protected $guarded = [];

    public $incrementing = false;


}
