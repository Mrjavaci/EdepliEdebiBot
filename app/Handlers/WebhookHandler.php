<?php

namespace App\Handlers;

use Illuminate\Support\Stringable;

class WebhookHandler extends \DefStudio\Telegraph\Handlers\WebhookHandler
{
    public function __call(string $name, array $arguments)
    {
        if (!class_exists(\Illuminate\Support\Str::studly($name . 'Command'))) {
            $this->listAllCommands();
        }
        (new $name)->handleCommand();
    }

    protected function listAllCommands()
    {
        $this->chat->html('<bold>Bahsi Geçen Komut Bulunamamıştır. Örnek komutlar aşağıdadır.</bold>');
    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->listAllCommands();
    }


}
