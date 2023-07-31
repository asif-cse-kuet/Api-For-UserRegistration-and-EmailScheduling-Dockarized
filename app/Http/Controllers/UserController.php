<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Services\GmailService;
use App\Jobs\WelcomeEmailJob;

class UserController extends Controller
{
    private $gmailService;

    public function __construct(GmailService $gmailService)
    {
        $this->gmailService = $gmailService;
    }

    public function register(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email'
            ]);

            // If validation fails, return error response
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            // Save the user's email to the database
            $user = new User();
            $user->email = $request->input('email');
            $user->save();

            // Send welcome email asynchronously
            WelcomeEmailJob::dispatch($request->input('email'));

            // Return a success response
            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'Something went wrong. Please try again later.'], 500);
        }
    }
}
