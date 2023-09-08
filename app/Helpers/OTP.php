<?php

namespace App\Helpers;

use App\Models\OTP as ModelsOTP;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OTP
{
    public static function generate(int $userId)
    {
        $user = User::with('otp')->find($userId);
        if ($user->otp) {
            $user->otp->delete();
        }

        return ModelsOTP::create([
            'user_id' => $user->id,
            'code' => rand(1000, 9999),
            'expires_at' => now()->addMinutes(15),
        ]);
    }

    public static function verify(int $providedOtp)
    {
        $userOtp = Auth::user()->otp;

        if (! $userOtp || $providedOtp != $userOtp->code || now()->gt($userOtp->expires_at)) {
            return false;
        }

        Auth::user()->email_verified_at = now();
        Auth::user()->save();

        $userOtp->delete();

        return true;
    }
}
