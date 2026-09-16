<?php

namespace Hitrov\Notification;

use Hitrov\HttpClient;
use Hitrov\Interfaces\NotifierInterface;

class Discord implements NotifierInterface
{
    public function notify(string $message): array
    {
        $webhookUrl = getenv('DISCORD_WEBHOOK_URL');

        $body = json_encode([
            'content' => mb_substr($message, 0, 1900), // Discord limite à 2000 caractères
        ]);

        $curlOptions = [
            CURLOPT_URL => $webhookUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $body,
        ];

        return HttpClient::getResponse($curlOptions);
    }

    public function isSupported(): bool
    {
        return !empty(getenv('DISCORD_WEBHOOK_URL'));
    }
}
