<?php

namespace Services\Telegram;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramBotApiTest extends TestCase
{

    /**
     * @test
     * @return void
     * @throws Exceptions\TelegramApiException
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

    /**
     * @test
     * @return void
     */
    public function it_send_message_success_by_fake_instance(): void
    {
        TelegramBotApi::fake()->returnTrue();

        //dd(app(TelegramBotApiContract::class));
        $result = app(TelegramBotApiContract::class)::sendMessage('', 1, 'Testing');

        $this->assertTrue($result);
    }

    /**
     * @test
     * @return void
     */
    public function it_send_message_fail_by_fake_instance(): void
    {
        TelegramBotApi::fake()
            ->returnFalse();

        $result = app(TelegramBotApiContract::class)::sendMessage('', 1, 'Testing');

        $this->assertFalse($result);
    }
}
