<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Jobs\WelcomeEmailJob;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = User::create([
            'email' => $request->validated('email'),
        ]);

        WelcomeEmailJob::dispatch($user->email);

        return response()->json([
            'message' => 'User registration successful.',
            'data' => [
                'email' => $user->email,
            ],
        ], 201);
    }
}
