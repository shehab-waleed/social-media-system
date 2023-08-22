<?php

namespace App\Models;

use App\Notifications\OtpNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OTP extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'user_otp';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generate(int $userId)
    {
        $user = User::with('otp')->find($userId);
        if ($user->otp) {
            $user->otp->delete();
        }

        $otp = Otp::create([
            'user_id' => $user->id,
            'code' => rand(1000, 9999),
            'expires_at' => now()->addMinutes(15),
        ]);

        $user->notify(new OtpNotification($otp->code));

        return $otp ? true : false;
    }

    public static function verify($providedOtp)
    {
        $userOtp = Auth::user()->otp;

        if (! $userOtp || $providedOtp != $userOtp->code || now()->gt($userOtp->expires_at)) {
            return false;
        }

        Auth::user()->update(['email_verified_at' => now()]);
        $userOtp->delete();

        return true;
    }
}
