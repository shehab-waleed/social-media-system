<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\OTP;
use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Notifications\OtpNotification;
use App\Http\Requests\StoreRegisterRequest;

class RegisterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegisterRequest $request)
    {
        if ($request->has('photo')) {
            $photoPath = $request->file('photo')->store('photos');
            $validatedData['photo'] = $photoPath;
        }

        $validatedData = $request->validated();
        $user = User::create($validatedData);

        $otp = OTP::generate($user->id);
        $user->notify(new OtpNotification($otp->code));

        $user->token = $user->createToken('userToken')->plainTextToken;
        $user->photo = $photoPath ?? null;

        return ApiResponse::send(JsonResponse::HTTP_CREATED, 'User registered successfully .', new UserResource($user));
    }
}
