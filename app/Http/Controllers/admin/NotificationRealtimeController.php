<?php

namespace App\Http\Controllers\Admin;

use App\Events\NotificationCreated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class NotificationRealtimeController extends Controller
{
    /**
     * Display notification test page.
     */
    public function index()
    {
        return Inertia::render('admin/Notifications/Index');
    }

    /**
     * Send a test notification.
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|max:255',
        ]);

        // Tạo notification trong database
        $notificationId = DB::table('notifications')->insertGetId([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'data' => json_encode($request->data ?? []),
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Lấy notification vừa tạo
        $notification = DB::table('notifications')->where('id', $notificationId)->first();

        // Broadcast real-time notification
        event(new NotificationCreated([
            'id' => $notification->id,
            'user_id' => $notification->user_id,
            'type' => $notification->type,
            'title' => $notification->title,
            'message' => $notification->message,
            'data' => json_decode($notification->data, true),
            'is_read' => (bool) $notification->is_read,
            'created_at' => $notification->created_at,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Notification sent successfully',
            'notification' => $notification,
        ]);
    }

    /**
     * Get notifications for a user.
     */
    public function getUserNotifications(Request $request)
    {
        $userId = Auth::id(); // Default user 1

        $notifications = DB::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    /**
     * Get notifications for header dropdown (compatible with existing API).
     */
    public function getHeaderNotifications(Request $request)
    {
        $userId = Auth::id(); // Default user 1 or get from auth

        $notifications = DB::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => json_decode($notification->data, true),
                    'isRead' => (bool) $notification->is_read,
                    'time' => $this->formatNotificationTime($notification->created_at),
                    'created_at' => $notification->created_at,
                ];
            });

        $unreadCount = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Format notification time for display.
     */
    private function formatNotificationTime($createdAt)
    {
        $date = new \DateTime($createdAt);
        $now = new \DateTime();
        $diff = $now->getTimestamp() - $date->getTimestamp();

        $minutes = floor($diff / 60);
        $hours = floor($diff / 3600);
        $days = floor($diff / 86400);

        if ($minutes < 1) return 'Vừa xong';
        if ($minutes < 60) return $minutes . ' phút trước';
        if ($hours < 24) return $hours . ' giờ trước';
        if ($days < 7) return $days . ' ngày trước';

        return $date->format('d/m/Y');
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->update([
                'is_read' => true,
                'updated_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $userId = Auth::id();

        DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'updated_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }
}
