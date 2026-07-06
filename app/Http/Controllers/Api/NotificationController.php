<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * GET /api/notifications/unread
     */
    public function getUnread(Request $request)
    {
        $user = $request->user();
        $notifications = $user->unreadNotifications;

        return response()->json([
            'data' => $notifications,
            'count' => $notifications->count()
        ]);
    }

    /**
     * POST /api/notifications/read-all
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'Semua notifikasi telah dibaca']);
    }

    /**
     * POST /api/notifications/{id}/read
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notifikasi dibaca']);
        }

        return response()->json(['message' => 'Notifikasi tidak ditemukan'], 404);
    }
}
