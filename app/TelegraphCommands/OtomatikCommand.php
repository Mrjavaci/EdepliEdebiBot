<?php

namespace App\TelegraphCommands;

use App\Handlers\WebhookHandler;
use App\Interfaces\TelegraphCommandInterface;
use App\Models\Ignores;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Models\TelegraphChat;

class OtomatikCommand implements TelegraphCommandInterface
{

    private WebhookHandler $webhookHandler;

    private TelegraphChat $telegraphChat;

    public static function getDescription()
    {
        return 'Otomatik mesaj gönderme sistemini açıp-kapatır';
    }


    public function setChat(TelegraphChat $telegraphChat): self
    {
        $this->telegraphChat = $telegraphChat;
        return $this;
    }

    public function setHandler(WebhookHandler $webhookHandler): void
    {
        $this->webhookHandler = $webhookHandler;
    }

    public function handleCommand(mixed $arguments = null): void
    {
        $status = $this->webhookHandler->getData()->get('status');
        if (!$status) {
            $this->sendActiveOrPassiveCommands();
            return;
        }
        if ($status === 'on') {
            Ignores::query()->where('chat_id', $this->telegraphChat->chat_id)->delete();
            $this->webhookHandler->sendHtml('Artık Size Otomatik Mesajlar Gönderilecektir.');
            return;
        }
        if ($status === 'off') {
            Ignores::query()->create(['chat_id' => $this->telegraphChat->chat_id]);
            $this->webhookHandler->sendHtml('Artık Size Otomatik Mesajlar Gönderilmeyecektir.');
        }

    }

    protected function sendActiveOrPassiveCommands()
    {
        $activeButton = Button::make('Aktif')
            ->action('otomatik')
            ->param('status', 'on');
        $passiveButton = Button::make('Pasif')
            ->action('otomatik')
            ->param('status', 'off');

        $this->webhookHandler->sendButtons('Otomatik mesaj gönderim seçimi yapınız.', [
            $activeButton,
            $passiveButton
        ]);
    }
}
