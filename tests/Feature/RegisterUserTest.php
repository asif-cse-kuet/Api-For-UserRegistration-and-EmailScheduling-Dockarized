<?php

namespace Tests\Feature;

use App\Jobs\WelcomeEmailJob;
use App\Mail\WelcomeEmail;
use App\Models\User;
use App\Services\GmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_successfully(): void
    {
        Queue::fake();

        $response = $this->postJson('/api/register', [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User registration successful.',
                'data' => [
                    'email' => 'user@example.com',
                ],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'user@example.com']);
        Queue::assertPushed(WelcomeEmailJob::class);
    }

    public function test_register_duplicate_email_returns_422(): void
    {
        User::factory()->create(['email' => 'user@example.com']);

        $response = $this->postJson('/api/register', [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ]);
    }

    public function test_register_invalid_email_returns_422(): void
    {
        $response = $this->postJson('/api/register', [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ]);
    }

    public function test_welcome_email_job_sends_mail(): void
    {
        Mail::fake();

        $job = new WelcomeEmailJob('user@example.com');
        $job->handle(app(GmailService::class));

        Mail::assertSent(WelcomeEmail::class, function (WelcomeEmail $mail) {
            return $mail->hasTo('user@example.com');
        });
    }

    public function test_health_endpoint_returns_ok(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'database' => 'connected',
            ]);
    }
}
