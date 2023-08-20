<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = auth()->user()->notifications;

        if ($notifications->count() == 0) {
            return ApiResponse::send(200, 'User does not have any notifications', []);
        } else {
            return ApiResponse::send(200, 'Notificaitons retireved successfully. ', $notifications);
        }
    }

    public function readAll()
    {
        $user = auth()->user();

        if ($user->unreadNotifications()->count() == 0) {
            return ApiResponse::send(200, 'User does not has any unread notifications .', []);
        } else {
            foreach ($user->notifications as $notification) {
                $notification->markAsRead();
            }

            return ApiResponse::send(200, 'Notifications marked as readed successfully .', []);
        }
    }
}
