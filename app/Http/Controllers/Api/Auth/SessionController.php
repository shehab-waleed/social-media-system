<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            return ApiResponse::send(JsonResponse::HTTP_OK, 'User logged in successfully .', new UserResource($user));
        } else {
            return ApiResponse::send(JsonResponse::HTTP_UNAUTHORIZED, 'User credentials does not works', null);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::send(JsonResponse::HTTP_NO_CONTENT, 'Logged out successfully .', []);
    }
}
