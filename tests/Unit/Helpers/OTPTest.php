<?php

use App\Helpers\OTP;
use App\Models\User;

beforeEach(function(){
   $this->user = User::factory()->create();
});

it('creates OTP for the user', function (){
    $this->assertDatabaseCount('user_otp', 0);

    $otp = OTP::generate($this->user->id);

    expect($otp->user_id)->toBe($this->user->id)
        ->and($otp->expires_at)->toBeGreaterThan(now());
    $this->assertDatabaseCount('user_otp', 1);
});

it('checks if OTP is valid', function (){
    $otp = OTP::generate($this->user->id);

    $result = OTP::verify($otp->code, $this->user);

    expect($result)->toBeTrue();
});

it('checks if OTP is invalid', function (){
    $otp = OTP::generate($this->user->id);

    $result = OTP::verify(9483, $this->user);

    expect($result)->toBeFalse();
});
