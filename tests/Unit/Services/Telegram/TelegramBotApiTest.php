<?php

namespace Services\Telegram;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramBotApiTest extends TestCase
{

    /**
     * @test
     * @return void
     */
    public function is_send_message_success(): void
    {
 //       Http::allowStrayRequests();// когда нужны реальные запросы, можно вызвать этот метод
        Http::fake([
            TelegramBotApi::HOST . '*' => Http::response(['ok' => true])
        ]);

        $result = TelegramBotApi::sendMessage('',1,'Testing');
        $this->assertTrue($result);
    }

}
