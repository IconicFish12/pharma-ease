<?php

namespace App\Listeners;

use App\Events\LowStockMedicine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class LowStockNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LowStockMedicine $event): void
    {
        $payload = [
            'type' => 'low_stock',
            'medicine_id' => $event->medicine->medicine_id,
            'medicine_name' => $event->medicine->medicine_name,
            'remaining_stock' => $event->medicine->stock,
            'timestamp' => now()->toDateTimeString()
        ];

        Redis::publish('notification_channel', json_encode($payload));

        Log::info("Low stock alert for {$event->medicine->medicine_name} sent to Redis.");
    }
}
