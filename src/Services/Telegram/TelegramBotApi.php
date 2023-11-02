<?php

namespace Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\Exceptions\TelegramApiException;
use Throwable;

class TelegramBotApi implements TelegramBotApiContract
{

    public const HOST = 'https://api.telegram.org/bot';

    public static function fake(): TelegramBotApiFake
    {
        return app()->instance(TelegramBotApiContract::class, new TelegramBotApiFake());
    }

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
        } catch (Throwable $e) {
            throw new TelegramApiException('Failed to send message to Telegram: ' . $e->getMessage(), 0, $e);
        }
    }
}
