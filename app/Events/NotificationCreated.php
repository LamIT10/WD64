<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notificationData;

    /**
     * Create a new event instance.
     */
    public function __construct(array $notificationData)
    {
        $this->notificationData = $notificationData;
        Log::info('NotificationCreated event fired for user: ' . $notificationData['user_id']);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            // Channel riêng cho user cụ thể
            new Channel('notifications.user.' . $this->notificationData['user_id']),
            // Channel chung cho tất cả notifications
            new Channel('notifications.all'),
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
            'notification' => $this->notificationData,
            'timestamp' => now()->toISOString(),
        ];
    }
}
