<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class GmailService
{
    public function sendWelcomeEmail($email)
    {
        try {
            // You can customize the welcome email content here
            $emailContent = 'Welcome to our website!';

            // Send the email using Laravel's built-in mail functionality
            Mail::raw($emailContent, function ($message) use ($email) {
                $message->to($email)->subject('Welcome to Our Website. You are registered user now.');
            });
        } catch (\Exception $e) {
            // Handle email sending errors
            // You can log the error or perform any other action as needed
            // For example:
            Log::error('Email sending failed: ' . $e->getMessage());

            // You can also return a response in case of failure
            // For example:
            return response()->json(['error' => 'Failed to send welcome email'], 500);
        }
    }
}

