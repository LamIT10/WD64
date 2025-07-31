<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function create(string $type, string $title, string $message, array $data = []): Notification
    {
        return Notification::create([
            'user_id' => Auth::id(), // Per user hiện tại (có thể mở rộng cho nhiều user)
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
