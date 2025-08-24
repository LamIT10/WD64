<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    protected $notificationRepository;
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function index()
    {

        $notificationsQuery = Notification::where('user_id', Auth::id())
            ->select(['id', 'type', 'title', 'message', 'created_at', 'is_read', 'data']);

        $notifications = $notificationsQuery
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'time' => $n->created_at,
                    'isRead' => $n->is_read,
                    'data' => $n->data,
                ];
            });

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        if ($notification->user_id === Auth::id()) {
            $notification->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())->where('is_read', false)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
    public function showAll(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $notifications = $this->notificationRepository->getAllNotifications($perPage);

        return Inertia::render('admin/Notifications/List', [
            'notifications' => $notifications,
        ]);
    }
}
