<?php

namespace App\Services\Telegram;

use App\Exceptions\TelegramApiException;
use Illuminate\Support\Facades\Http;

class TelegramBotApi
{

    public const HOST = 'https://api.telegram.org/bot';
    public static function sendMessage(string $token, int $chatId, string $text): bool
    {
        try {
            $response = Http::get(self::HOST . $token . '/sendMessage', [
                'chat_id' => $chatId,
                'text' => $text
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['ok']) && $responseData['ok'] === true) {
                    return true;
                } else {
                    throw new TelegramApiException('Telegram API returned an error: ' . json_encode($responseData));
                }
            } else {
                throw new TelegramApiException('Telegram API request was not successful.');
            }
        } catch (\Exception $e) {
            throw new TelegramApiException('Failed to send message to Telegram: ' . $e->getMessage(), 0, $e);
        }
    }
}
