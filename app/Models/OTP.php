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


}
