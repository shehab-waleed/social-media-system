<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\OTP;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\OtpNotification;
use Auth;

class OtpController extends Controller
{
    public function verify(Request $request)
    {
        $data = $request->validate([
            'otp' => ['required', 'min:1', 'max:5'],
        ]);

        if (!OTP::verify($data['otp'])) {
            return ApiResponse::send(422, 'There is an error in OTP code', ['is_verified' => false]);
        }

        return ApiResponse::send(200, 'Account verified successfully', ['is_verified' => true]);
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $otp = OTP::generate(Auth::user()->id);
        Auth::user()->notify(new OtpNotification($otp->code));
        return ApiResponse::send(201, 'OTP generated successfully .');

    }
}
