<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Comment;

class CommentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comments;

    public function __construct(Comment $comments)
    {
        $this->comments = $comments;
    }

    public function broadcastOn()
    {
        return [
            new Channel('comment')
        ];
    }
}
