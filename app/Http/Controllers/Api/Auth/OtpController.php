<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Helpers\OtpCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function verify(Request $request)
    {
        $data = $request->validate([
            'otp' => ['required', 'min:1', 'max:5'],
        ]);

        if (! OtpCode::verify($data['otp'])) {
            return ApiResponse::send(200, 'There is an error in OTP code', ['is_verified' => false]);
        }

        return ApiResponse::send(200, 'Account verified successfully', ['is_verified' => true]);
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        if (OtpCode::generate($data['user_id'])) {
            return ApiResponse::send(201, 'OTP generated successfully .');
        }

        return ApiResponse::send(200, 'Something went wrong');

    }
}
