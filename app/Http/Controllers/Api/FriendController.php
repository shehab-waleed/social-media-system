<?php

namespace App\Http\Controllers\api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\FriendRequest;
use App\Models\User;
use App\Notifications\FriendRequestSent;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function sendRequest (Request $request)
    {
        $request->validate([
            'receiver_id' => ['required', "exists:users,id"],
        ]);
        $sender = auth()->user();
        $friendRequest  =  FriendRequest::with('sender')->create([
            'receiver_id' =>$request->receiver_id,
            'sender_id' => $sender->id,
            'status' => 'pending',
        ]);
        $receiver = User::findOrFail($request->receiver_id);
        $receiver->notify(new FriendRequestSent($friendRequest,'pending'));
        return ApiResponse::send('200','Friend request sent' ,NULL);
    }
}
