<?php

namespace App\Services;

use App\Events\NotificationCreated;
use App\Models\Notification;
use App\Models\User;
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
    public function notifyAll(string $type, string $title, string $message, array $data = [])
    {
        $actor = Auth::user();
        $allUsers = User::all();
        foreach ($allUsers as $user) {
            if ($user->id == $actor->id) {
                continue; // Bỏ qua user thực hiện, vì đã có notification riêng từ hàm create
            }
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => array_merge($data, [
                    'actor_id' => $actor->id,
                    'actor_name' => $actor->name,
                ]),
                'is_read' => false,
            ]);
            event(new NotificationCreated([
                'id' => $notification->id,
                'user_id' => $user->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'message' => $notification->message,
                'data' => $notification->data,
                'is_read' => false,
                'created_at' => $notification->created_at,
            ]));
        }
    }
}
