<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId)
    {
        //
        $user = User::find($userId);

        if (!$user)
            return ApiResponse::send(200, 'User not found', []);

        $notifications = Notification::where('notifiable_id', $user->id)->get();

        if ($notifications->count() == 0)
            return ApiResponse::send(200, 'User does not have any notifications', []);
        else
            return ApiResponse::send(200, 'Notificaitons retireved successfully. ', $notifications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function readAll($userId)
    {
        $user = User::find($userId);

        if (!$user)
            return ApiResponse::send(200, 'User not found', []);

        if ($user->unreadNotifications()->count() == 0)
            return ApiResponse::send(200, 'User does not has any unread notifications .', []);
        else {
            foreach ($user->notifications as $notification) {
                $notification->markAsRead();
            }
            return ApiResponse::send(200, 'Notifications marked as readed successfully .', []);
        }
    }
}
