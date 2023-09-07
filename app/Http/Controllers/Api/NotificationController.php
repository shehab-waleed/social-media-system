<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = auth()->user()->notifications;

        if ($notifications->count() == 0) {
            return ApiResponse::send(204, 'User does not have any notifications');
        } else {
            return ApiResponse::send(200, 'Notificaitons retireved successfully. ', $notifications);
        }
    }

    public function read(int $notificaionId)
    {
        $notificaion = Auth::user()->notifications->where('id', 3);

        if (!$notificaion) {
            return ApiResponse::send(404, 'Notificaion not found.');
        }

        $notificaion->markAsRead();
        return ApiResponse::send(200, 'Notificaion marked as read sucessfully.');
    }

    public function readAll()
    {
        if (auth()->user()->unreadNotifications()->count() == 0) {
            return ApiResponse::send(204, 'User does not has any unread notifications .');
        } else {
            foreach (auth()->user()->notifications as $notification) {
                $notification->markAsRead();
            }
            return ApiResponse::send(200, 'Notifications marked as readed successfully .', [  ]);
        }
    }
}
