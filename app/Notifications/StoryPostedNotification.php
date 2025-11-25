<?php

namespace App\Notifications;

use App\Models\Story;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class StoryPostedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $story;
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(Story $story)
    {
        $this->story = $story;
        $this->user = $story->user;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'message' => $this->user->name . ' posted a new story!',
            'type' => 'story',
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'message' => $this->user->name . ' posted a new story!',
            'type' => 'story',
            'story_id' => $this->story->id,
        ];
    }
}
