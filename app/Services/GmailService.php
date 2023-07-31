<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class GmailService
{
    public function sendWelcomeEmail($email)
    {
        // You can customize the welcome email content here
        $emailContent = 'Welcome to our website!';

        // Send the email using Laravel's built-in mail functionality
        Mail::raw($emailContent, function ($message) use ($email) {
            $message->to($email)->subject('Welcome to Our Website');
        });
    }
}
