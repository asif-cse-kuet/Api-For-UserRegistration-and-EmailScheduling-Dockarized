<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'message' => $validator->errors()], 400);
        }

        // Save the user's email to the database
        $user = User::create([
            'email' => $request->input('email')
        ]);

        // Send welcome email asynchronously (we will implement this in Step 8)
        // ...

        // Return a success response
        return response()->json(['message' => 'User registered successfully'], 201);
    }
}
