<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
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

        $isUserAuthanticated = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        if ($isUserAuthanticated) {
            $user = Auth::user();
            $data['Token'] = $user->createToken('userToken')->plainTextToken;
            $data['First Name'] = $user->first_name;
            $data['Last Name'] = $user->last_name;
            $data['Email'] = $user->email;

            $user->generateOTP();
            return ApiResponse::send(200, 'User logged in successfully .', $data);
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
