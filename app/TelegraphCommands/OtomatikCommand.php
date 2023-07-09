<?php

namespace App\TelegraphCommands;

use App\Handlers\WebhookHandler;
use App\Interfaces\TelegraphCommandInterface;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Models\TelegraphChat;

class OtomatikCommand implements TelegraphCommandInterface
{

    private WebhookHandler $webhookHandler;

    public static function getDescription()
    {
        return 'Otomatik mesaj gönderme sistemini açıp-kapatır';
    }


    public function setHandler(WebhookHandler $webhookHandler): void
    {
        $this->webhookHandler = $webhookHandler;
    }

    public function handleCommand(mixed $arguments = null): void
    {
        if (!$this->webhookHandler->getData()->get('status')) {
            $this->sendActiveOrPassiveCommands();
        }

    }

    protected function sendActiveOrPassiveCommands()
    {
        $activeButton = Button::make('Aktif')
            ->action('otomatik')
            ->param('status', 1);
        $passiveButton = Button::make('Pasif')
            ->action('otomatik')
            ->param('status', 0);

        $this->webhookHandler->sendButtons('Otomatik mesaj gönderim seçimi yapınız.', [
            $activeButton,
            $passiveButton
        ]);
    }
}
