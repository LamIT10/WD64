<?php

namespace App\Services;

use App\Events\NotificationCreated;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function create(string $type, string $title, string $message, array $data = []): Notification
    {
        $notification = Notification::create([
            'user_id' => Auth::id(),
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        // Truyền đủ trường cho event realtime
        event(new NotificationCreated([
            'id' => $notification->id,
            'user_id' => $notification->user_id,
            'type' => $notification->type,
            'title' => $notification->title,
            'message' => $notification->message,
            'data' => $notification->data,
            'created_at' => $notification->created_at ? $notification->created_at->toISOString() : now()->toISOString(),
        ]));

        return $notification;
    }
}
