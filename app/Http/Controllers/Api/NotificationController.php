<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = auth()->user()->notifications;

        if ($notifications->count() == 0) {
            return ApiResponse::send(JsonResponse::HTTP_NOT_FOUND, 'User does not have any notifications');
        } else {
            return ApiResponse::send(JsonResponse::HTTP_OK, 'Notificaitons retireved successfully. ', $notifications);
        }
    }

    public function read(int $notificaionId)
    {
        $notificaion = Auth::user()->notifications->where('id', 3);

        if (!$notificaion) {
            return ApiResponse::send(JsonResponse::HTTP_NOT_FOUND, 'Notificaion not found.');
        }

        $notificaion->markAsRead();
        return ApiResponse::send(JsonResponse::HTTP_OK, 'Notificaion marked as read sucessfully.');
    }

    public function readAll()
    {
        if (auth()->user()->unreadNotifications()->count() == 0) {
            return ApiResponse::send(JsonResponse::HTTP_NOT_FOUND, 'User does not has any unread notifications .');
        } else {
            foreach (auth()->user()->notifications as $notification) {
                $notification->markAsRead();
            }
            return ApiResponse::send(JsonResponse::HTTP_OK, 'Notifications marked as readed successfully .', [  ]);
        }
    }
}
