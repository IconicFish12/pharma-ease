<?php

namespace App\Events;

use App\Models\Medicine;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowStockMedicine implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $medicine;

    /**
     * Create a new event instance.
     */
    public function __construct(Medicine $medicine)
    {
        $this->medicine = $medicine;
    }

    /**
    * The event's broadcast name.
    */

    public function broadcastAs(): string
    {

        return 'low.stock';
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
            'type' => 'low_stock',
            'medicine_id' => $this->medicine->medicine_id,
            'medicine_name' => $this->medicine->medicine_name,
            'remaining_stock' => $this->medicine->stock,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
