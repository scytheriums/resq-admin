<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $botToken;
    protected $chatId;

    public function __construct($chatId)
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->chatId = $chatId;
    }

    public function sendMessage($message)
    {
        if (empty($this->botToken) || empty($this->chatId)) {
            Log::warning('Telegram bot token or chat ID not configured');
            return false;
        }

        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
        
        try {
            $response = Http::post($url, [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]);

            if (!$response->successful()) {
                Log::error('Failed to send Telegram notification', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error sending Telegram notification: ' . $e->getMessage());
            return false;
        }
    }
}
