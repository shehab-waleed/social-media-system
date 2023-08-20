<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = Cache::remember('users', 60 * 60, function () {
            return User::all();
        });

        if ($users->count() > 0) {
            return ApiResponse::send(200, 'Users retireved successfully .', UserResource::collection($users));
        } else {
            return ApiResponse::send(200, 'No users exists .', []);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
        $record = User::create($request->validated());

        if ($record) {
            return ApiResponse::send(200, 'User Created successfully .', new UserResource($record));
        } else {
            return ApiResponse::send(200, 'Something went wrong .', []);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::find($id);

        if ($user) {
            return ApiResponse::send(200, 'User retrieved successfully .', new UserResource($user));
        } else {
            return ApiResponse::send(200, 'User not found .', null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::send(200, 'User not found', null);
        }

        $newData = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'username' => ['string', Rule::unique('users')->ignore($id)],
            'email' => ['string', Rule::unique('users')->ignore($user->id)],
            'password' => 'password',
            'is_admin' => 'boolean',
        ]);

        $user->update($newData);
        $user->save();

        return ApiResponse::send(200, 'User updated successfully', new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::send(200, 'User not found', null);
        }

        $user->delete();

        return ApiResponse::send(200, 'User deleted successfully .', null);
    }
}
