<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SenMessage implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $username;

    public $message;
    /**
    * Create a new event instance.
    *
    * @return void
    */

    public function __construct( $username, $message ) {
        $this->username = $username;
        $this->message = $message;
    }

    /**
    * Get the channels the event should broadcast on.
    *
    * @return \Illuminate\Broadcasting\Channel|array
    */

    public function broadcastOn() {
        return ['message-sended'];
        // return new PrivateChannel( 'channel-name' );
    }

    public function broadcastAs() {
        return 'message-send-event';
    }
}
