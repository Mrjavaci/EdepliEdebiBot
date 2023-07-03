<?php

namespace App\Handlers;

class WebhookHandler extends \DefStudio\Telegraph\Handlers\WebhookHandler
{
    public function __call(string $name, array $arguments)
    {
        if (!class_exists(\Illuminate\Support\Str::studly($name . 'Command'))) {
            $this->chat->html('<bold>Bahsi Geçen Komut Bulunamamıştır. Örnek komutlar aşağıdadır.</bold>');
        }
        (new $name)->handleCommand();
    }
}
