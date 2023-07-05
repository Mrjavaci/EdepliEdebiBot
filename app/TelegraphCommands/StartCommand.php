<?php

namespace App\TelegraphCommands;

use App\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;

class StartCommand implements \App\Interfaces\TelegraphCommandInterface
{
    private TelegraphBot $telegraphBot;
    private TelegraphChat $telegraphChat;
    private WebhookHandler $handler;

    public static function getDescription()
    {
        return 'Başlangıç Komutu';
    }

    public function handleCommand(mixed $arguments = null): void
    {
        $this->telegraphChat->html('<b>Hoşgeldiniz!</b>')->send();
        $this->handler->listAllCommands();
    }

    public function setBot(TelegraphBot $telegraphBot): self
    {
        $this->telegraphBot = $telegraphBot;
        return $this;
    }

    public function setChat(TelegraphChat $telegraphChat): self
    {
        $this->telegraphChat = $telegraphChat;
        return $this;
    }

    public function setHandler(WebhookHandler $webhookHandler): self
    {
        $this->handler = $webhookHandler;
        return $this;
    }
}
