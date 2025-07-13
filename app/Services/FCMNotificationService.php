<?php

namespace App\Services;

use Google\Client;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Log; // Import Log Facade
use Throwable; // Import Throwable

class FCMNotificationService
{
    public static function send($fcmToken, $title, $body, $data = [])
    {
        try { // <- Mimitian blok try
            $serviceAccountPath = storage_path('app/firebase-service-account.json');
            $projectId = 'resqin-3dfe5';

            $googleClient = new Client();
            $googleClient->setAuthConfig($serviceAccountPath);
            $googleClient->addScope('https://www.googleapis.com/auth/firebase.messaging');
            
            Log::info('Mencoba mengambil access token...');
            $accessToken = $googleClient->fetchAccessTokenWithAssertion()['access_token'];
            Log::info('Access token berhasil didapat.');

            $httpClient = new HttpClient();
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body
                    ],
                    'data' => $data
                ]
            ];

            Log::info('Mencoba mengirim notifikasi ke FCM...', ['url' => $url, 'payload' => $payload]);
            $response = $httpClient->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload
            ]);
            
            $responseBody = $response->getBody()->getContents();
            Log::info('Notifikasi berhasil dikirim ke FCM.', ['response' => $responseBody]);
            return $responseBody;

        } catch (Throwable $e) { // <- Tangkap kasalahan nanaon
            // Catet kasalahanana kana file log Laravel
            Log::error('Gagal mengirim FCM Notifikasi: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Opsional: balikeun respons kasalahan
            return json_encode(['error' => 'Gagal mengirim notifikasi', 'message' => $e->getMessage()]);
        }
    }
}