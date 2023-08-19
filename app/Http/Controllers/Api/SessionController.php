<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreLoginRequest;

class SessionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoginRequest $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $user->token = $user->createToken('userToken')->plainTextToken;
            
            return ApiResponse::send(200, 'User logged in successfully .', new UserResource($user));
        } else {
            return ApiResponse::send(401, 'User credentials does not works', null);
        }
        ;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::send(200, 'Logged out successfully .', []);
    }
}
