<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\DriverLocation;

class DriverLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $driverId;
    public $tripId;
    public $location;

    /**
     * Create a new event instance.
     */
    public function __construct($driverId, $tripId, DriverLocation $location)
    {
        $this->driverId = $driverId;
        $this->tripId = $tripId;
        $this->location = $location;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // Broadcast to admin channel for real-time updates
            new PrivateChannel('admin.driver-locations'),
            // Broadcast to specific trip tracking channel
            new PrivateChannel('trip.location.' . $this->tripId),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'driver_id' => $this->driverId,
            'trip_id' => $this->tripId,
            'location' => [
                'id' => $this->location->id,
                'location_name' => $this->location->location_name,
                'location_detail' => $this->location->location_detail,
                'latitude' => $this->location->latitude,
                'longitude' => $this->location->longitude,
                'stop_index' => $this->location->stop_index,
                'status' => $this->location->status,
                'created_at' => $this->location->created_at,
            ]
        ];
    }
}
