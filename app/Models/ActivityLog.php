<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityLogFactory> */
    use HasUuids;
    use HasFactory;

    protected $table = 'activity_logs';

    protected $primaryKey = 'log_id';

    protected $guarded = ['log_id', 'user_activities', 'activity_time'];

    public $timestamps = false;

    public $incrementing = false;
}
