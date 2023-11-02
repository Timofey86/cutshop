<?php

namespace Support\Logging\Telegram;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Services\Telegram\TelegramBotApi;
use Services\Telegram\TelegramBotApiContract;

class TelegramLoggerHandler extends AbstractProcessingHandler
{

    protected int $chatId;

    protected string $token;

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);
        parent::__construct($level);
        $this->token = $config['token'];
        $this->chatId = $config['chat_id'];
    }

    protected function write(LogRecord $record): void
    {
        $data = $record->toArray();
        app(TelegramBotApiContract::class)::sendMessage(
            $this->token,
            $this->chatId,
            $record['formatted']);
            //$record->formatted);
        //$record['formatted']
    }
}
