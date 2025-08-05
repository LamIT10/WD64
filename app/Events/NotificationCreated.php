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
        Log::info('NotificationCreated::__construct', $notificationData);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        Log::info('NotificationCreated::broadcastOn', [
            'user_channel' => 'notifications.user.' . $this->notificationData['user_id'],
            'all_channel' => 'notifications.all',
        ]);
        return [
            new Channel('notifications.user.' . $this->notificationData['user_id']),
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
        Log::info('NotificationCreated::broadcastWith', $this->notificationData);
        return [
            'notification' => $this->notificationData,
            'timestamp' => now()->toISOString(),
        ];
    }
}
