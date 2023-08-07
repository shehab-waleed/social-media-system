<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoginRequest;
use Auth;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoginRequest $request)
    {
        //
        $isUserAuthanticated = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        if ($isUserAuthanticated) {
            $user = Auth::user();
            $data['Token'] = $user->createToken('userToken')->plainTextToken;
            $data['First Name'] = $user->first_name;
            $data['Last Name'] = $user->last_name;
            $data['Email'] = $user->email;

            return ApiResponse::send(200, 'User logged in successfully .', $data);
        } else {
            return ApiResponse::send(401, 'User credentials does not works', null);
        }
        ;

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::send(200, 'Logged out successfully .', []);
    }
}
