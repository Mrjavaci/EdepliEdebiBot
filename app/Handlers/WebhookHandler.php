<?php

namespace App\Handlers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;

class WebhookHandler extends \DefStudio\Telegraph\Handlers\WebhookHandler
{
    protected function handleUnknownCommand(Stringable $text): void
    {
        Log::info('handleUnknownCommand');
        $this->handleCustomCommand((string)$text->after('/')->before(' ')->before('@'), (string)$text->after('@')->after(' '));
    }


    public function handleCustomCommand(string $name, $parameter): void
    {
        if (!class_exists(\Illuminate\Support\Str::studly($name . 'Command'))) {
            Log::info('!class_exists');

            $this->listAllCommands();
            return;
        }
        Log::info('class_exists');
        (new $name)->handleCustomCommand($parameter);
    }

    protected function listAllCommands(): void
    {
        $this->chat->html('<b>Bahsi Geçen Komut Bulunamamıştır. Örnek komutlar aşağıdadır.</b>')->send();
    }


}
