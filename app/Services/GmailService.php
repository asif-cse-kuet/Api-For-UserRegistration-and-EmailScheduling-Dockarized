<?php

namespace App\Services;

use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GmailService
{
    public function sendWelcomeEmail(string $email): void
    {
        try {
            Mail::to($email)->send(new WelcomeEmail());
        } catch (\Exception $e) {
            Log::error('Email sending failed', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
