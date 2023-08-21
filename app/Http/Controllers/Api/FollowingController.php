<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FollowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $followedUser)
    {

        if (!Auth::user()->following()->pluck('followed_id')->contains($followedUser->id))
            Auth::user()->following()->attach($followedUser->id);

        return ApiResponse::send(201, 'User followed successfully . ', null);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $followedUser)
    {
        Auth::user()->following()->detach($followedUser->id);

        return ApiResponse::send(200, 'User un followed successfully . ', null);
    }
}