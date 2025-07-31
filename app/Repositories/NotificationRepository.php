<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationRepository
{
    protected $model;

    public function __construct(Notification $notification)
    {
        $this->model = $notification;
    }
    public function getAllNotifications($perPage = 10)
    {
        return $this->model->where('user_id', Auth::id())
            ->latest()
            ->paginate($perPage)
            ->through(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'time' => $n->created_at->diffForHumans(),
                    'isRead' => $n->is_read,
                    'data' => $n->data,
                ];
            });
    }
}
