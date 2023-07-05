<?php

namespace App\Handlers;

use App\Interfaces\TelegraphCommandInterface;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Stringable;

class WebhookHandler extends \DefStudio\Telegraph\Handlers\WebhookHandler
{
    const ACTION_KEY = 'act';

    /**
     * should be equal self::ACTION_KEY
     * @return void
     */
    public function act()
    {
        if (!$this->data->get('command')) {
            return;
        }
        $this->handleCustomCommand($this->data->get('command'), null);
    }

    public function handleCustomCommand(string $name, $parameter): void
    {
        $className = '\\App\\TelegraphCommands\\' . \Illuminate\Support\Str::studly($name . 'Command');

        if (!class_exists($className)) {

            $this->listAllCommands();
            return;
        }

        app()->bind(TelegraphCommandInterface::class, function () use ($className, $parameter) {
            $instance = (new $className);
            if (method_exists($instance, 'setBot')) {
                $instance->setBot($this->bot);
            }
            if (method_exists($instance, 'setChat')) {
                $instance->setChat($this->chat);
            }
            if (method_exists($instance, 'setHandler')) {
                $instance->setHandler($this);
            }
            return $instance->handleCommand($parameter);
        });

        app()->make(TelegraphCommandInterface::class);
    }

    public function listAllCommands(): void
    {
        $buttons = collect(Storage::disk('commands')->allFiles())->map(function (string $fileName) {
            /**
             * @var $className TelegraphCommandInterface
             */
            $className = '\\App\\TelegraphCommands\\' . explode('.', $fileName)[0];
            $description = $className::getDescription();
            $command = '/' . lcfirst(explode('Command', $fileName)[0]);
            return Button::make($command . ' - ' . $description)
                ->action(self::ACTION_KEY)
                ->param('command', lcfirst(explode('Command', $fileName)[0]));
        })->toArray();

        $this->sendButtons('<b>Bahsi Geçen Komut Bulunamamıştır. Örnek komutlar aşağıdadır.</b>', $buttons);
    }

    /**
     * @param array $buttons
     * @return void
     */
    public function sendButtons($title, array $buttons): void
    {
        $this->chat->html($title)
            ->keyboard(Keyboard::make()->buttons($buttons))->setChatMenuButton()->send();

    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        Log::info('handleUnknownCommand');
        $this->handleCustomCommand((string)$text->after('/')->before(' ')->before('@'), (string)$text->after('@')->after(' '));
    }

    protected function sendHtml(string $message)
    {
        $this->chat->html($message)->send();
    }


}

