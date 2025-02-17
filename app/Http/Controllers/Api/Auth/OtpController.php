<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Helpers\OTP;
use App\Http\Controllers\Controller;
use App\Notifications\OtpNotification;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class OtpController extends Controller
{
    public function verify(Request $request)
    {
        $data = $request->validate([
            'otp' => ['required', 'digits:4'],
        ]);

        if (! OTP::verify($data['otp'], $request->user())) {
            return ApiResponse::send(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'There is an error in OTP code', ['is_verified' => false]);
        }

        return ApiResponse::send(JsonResponse::HTTP_OK, 'Account verified successfully', ['is_verified' => true]);
    }

    public function generate(Request $request)
    {

        $otp = OTP::generate(Auth::user()->id);
        Auth::user()->notify(new OtpNotification($otp->code));

        return ApiResponse::send(JsonResponse::HTTP_CREATED, 'OTP generated successfully .');

    }
}
