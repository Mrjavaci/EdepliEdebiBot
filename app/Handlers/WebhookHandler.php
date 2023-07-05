<?php

namespace App\Handlers;

use App\Interfaces\TelegraphCommandInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;

class WebhookHandler extends \DefStudio\Telegraph\Handlers\WebhookHandler
{
    public function handleUnknownCommandForTesting(Stringable $text): void
    {
        $this->handleUnknownCommand($text);
    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        Log::info('handleUnknownCommand');
        $this->handleCustomCommand((string)$text->after('/')->before(' ')->before('@'), (string)$text->after('@')->after(' '));
    }

    public function handleCustomCommand(string $name, $parameter): void
    {
        $className = '\\App\\TelegraphCommands\\' . \Illuminate\Support\Str::studly($name . 'Command');

        if (!class_exists($className)) {

            $this->listAllCommands();
            return;
        }

        app()->bind(TelegraphCommandInterface::class, function () use ($className, $parameter) {
            return (new $className)
                ->setBot($this->bot)
                ->setChat($this->chat)
                ->handleCommand($parameter);
        });
        app()->make(TelegraphCommandInterface::class);


    }

    protected function listAllCommands(): void
    {
        $this->sendHtml('<b>Bahsi Geçen Komut Bulunamamıştır. Örnek komutlar aşağıdadır.</b>');
    }

    protected function sendHtml(string $message)
    {
        $this->chat->html($message)->send();
    }


}

