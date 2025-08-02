<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    /**
     * Create a new event instance.
     */
    public function __construct($notificationData)
    {
        $this->notification = [
            'id' => $notificationData['id'] ?? null,
            'user_id' => $notificationData['user_id'],
            'type' => $notificationData['type'],
            'title' => $notificationData['title'],
            'message' => $notificationData['message'],
            'data' => $notificationData['data'] ?? null,
            'is_read' => $notificationData['is_read'] ?? false,
            'created_at' => $notificationData['created_at'] ?? now()->toISOString(),
        ];
        
        Log::info('NotificationEvent created for user: ' . $notificationData['user_id']);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            // Private channel cho user cụ thể
            new Channel('notifications.' . $this->notification['user_id']),
            // Public channel cho tất cả notifications (optional)
            new Channel('notifications'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'NotificationCreated';
    }

    /**
     * Data to broadcast with the event.
     */
    public function broadcastWith(): array
    {
        return [
            'notification' => $this->notification,
            'timestamp' => now()->toISOString(),
        ];
    }
}
