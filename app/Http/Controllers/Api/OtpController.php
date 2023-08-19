<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Helpers\ApiResponse;
use App\Helpers\OtpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\FlareClient\Api;

class OtpController extends Controller
{
    public function verify(Request $request)
    {
        $data = $request->validate([
            'otp' => ['required', 'min:1', 'max:5'],
        ]);

        if (!OtpCode::verify($data['otp']))
            return ApiResponse::send(200, 'There is an error in OTP code');

        return ApiResponse::send(200, 'Account verified successfully');
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        if (OtpCode::generate($data['user_id']))
            return ApiResponse::send(201, 'OTP generated successfully .');

        return ApiResponse::send(200, 'Something went wrong');

    }
}
