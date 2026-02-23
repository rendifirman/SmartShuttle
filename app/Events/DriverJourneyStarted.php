<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\DriverJourneyState;

class DriverJourneyStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $driverId;
    public $tripId;
    public $journeyState;

    public function __construct($driverId, $tripId, DriverJourneyState $journeyState)
    {
        $this->driverId = $driverId;
        $this->tripId = $tripId;
        $this->journeyState = $journeyState;
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
            'state' => [
                'current_stop_index' => $this->journeyState->current_stop_index,
                'status' => $this->journeyState->status,
                'started_at' => $this->journeyState->started_at,
                'total_stops' => $this->journeyState->total_stops,
            ]
        ];
    }
}
