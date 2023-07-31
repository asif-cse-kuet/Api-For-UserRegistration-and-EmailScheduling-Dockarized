<?php

namespace App\Mail;

use Google\Client;
use Google\Service\Gmail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

class GmailService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName(config('app.name'));
        $this->client->setClientId(config('services.gmail.client_id'));
        $this->client->setClientSecret(config('services.gmail.client_secret'));
        $this->client->setRedirectUri(config('services.gmail.redirect_uri'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        $accessToken = $this->getAccessToken();
        if ($accessToken) {
            $this->client->setAccessToken($accessToken);
        }
    }

    public function getAccessToken()
    {
        $accessToken = auth()->user()->gmail_access_token;
        if (!$accessToken || $accessToken->isExpired()) {
            $refreshToken = auth()->user()->gmail_refresh_token;
            if ($refreshToken) {
                try {
                    $accessToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
                    auth()->user()->update([
                        'gmail_access_token' => $accessToken,
                    ]);
                    return $accessToken;
                } catch (\Exception $e) {
                    Log::error('Error fetching access token with refresh token: ' . $e->getMessage());
                    return null;
                }
            } else {
                Log::error('User does not have Gmail refresh token.');
                return null;
            }
        }

        return $accessToken;
    }

    public function sendEmail(Mailable $email)
    {
        $service = new Gmail($this->client);
        $rawMessage = base64_encode($email->render());

        $message = new \Google\Service\Gmail\Message();
        $message->setRaw($rawMessage);

        try {
            $service->users_messages->send('me', $message);
            return true;
        } catch (\Exception $e) {
            Log::error('Error sending email via Gmail API: ' . $e->getMessage());
            return false;
        }
    }
}
