<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Services\GmailService;
use App\Models\User;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(string $userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $userEmail = $this->userEmail; // Use the correct variable name
        // Send the welcome email
        $welcomeEmail = new WelcomeEmail();
        $welcomeEmail->subject('Welcome to Your App');
        $welcomeEmail->to($userEmail);
        Mail::queue($welcomeEmail);
    }

}
