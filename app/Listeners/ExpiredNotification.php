<?php

namespace App\Listeners;

use App\Events\ExpiredMedicine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ExpiredNotification implements ShouldQueue
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
    public function handle(ExpiredMedicine $event): void
    {
        foreach ($event->medicines as $med) {
            $payload = [
                'type' => 'expired',
                'medicine_id' => $med->medicine_id, // Pastikan sesuai PK model Medicine (id/medicine_id)
                'medicine_name' => $med->medicine_name,
                'expired_date' => $med->expired_date,
                'timestamp' => now()->toDateTimeString()
            ];

            Redis::publish('notification_channel', json_encode($payload));
        }

        Log::info("Sent " . $event->medicines->count() . " expired notifications to Redis.");
    }
}
