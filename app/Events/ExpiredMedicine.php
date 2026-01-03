<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExpiredMedicine implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $medicines;

    /**
     * Create a new event instance.
     */
    public function __construct(Collection $medicines)
    {
        $this->medicines = $medicines;
    }

    /**
    * The event's broadcast name.
    */

    public function broadcastAs(): string

    {

        return 'expired.medicine';

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('inventory-channel'),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'type' => 'expired',
            'count' => $this->medicines->count(),
            'items' => $this->medicines->map(function ($med) {
                return [
                    'medicine_id' => $med->medicine_id ?? $med->id,
                    'medicine_name' => $med->medicine_name,
                    'expired_date' => $med->expired_date,
                ];
            })->toArray(),
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
