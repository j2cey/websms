<?php

namespace App\Events;

use App\Smsresult;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SmsresultEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $smsresult;
    public $campaign;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($campaign, $smsresult)
    {
        $this->smsresult = $smsresult;
        $this->campaign = $campaign;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('Smsresult-channel');
    }

    public function broadcastAs() {
        return 'SmsresultEvent';
    }

    public function broadcastWith() {
        return [
            'result' => $this->smsresult,
            'campaign' => $this->campaign
        ];
    }
}
