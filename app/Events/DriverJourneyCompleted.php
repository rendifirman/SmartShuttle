<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverJourneyCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $driverId;
    public $tripId;

    public function __construct($driverId, $tripId)
    {
        $this->driverId = $driverId;
        $this->tripId = $tripId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.driver-journeys'),
            new PrivateChannel('trip.journey.' . $this->tripId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'driver_id' => $this->driverId,
            'trip_id' => $this->tripId,
            'status' => 'completed'
        ];
    }
}
