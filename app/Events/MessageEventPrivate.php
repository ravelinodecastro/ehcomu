<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;
use App\Models\Chat;

    class MessageEventPrivate implements ShouldBroadcast
    {
        use Dispatchable, InteractsWithSockets, SerializesModels;

        public $user;
        public $chat;

        /**
         * Create a new event instance.
         *
         * @param  $comment
         *
         * @return void
         */

        public function __construct(User $user, Chat $chat)
        {
            //
            $this->user = $user;
            $this->chat = $chat;
        }

        /**
         * Get the channels the event should broadcast on.
         *
         * @return \Illuminate\Broadcasting\Channel|array
         */

        public function broadcastOn()
        {
           return new PrivateChannel('message-channel-private.' . $this->chat->id);
            
        }

    }