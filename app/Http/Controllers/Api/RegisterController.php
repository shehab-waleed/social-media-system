<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\OtpCode;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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
        OtpCode::generate($user->id);

        $user->token = $user->createToken('userToken')->plainTextToken;
        $user->photo = $photoPath ?? null;

        return ApiResponse::send(201, 'User registered successfully .', new UserResource($user));
    }

}
