<?php

namespace App\TelegraphCommands;

use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;

class StartCommand implements \App\Interfaces\TelegraphCommandInterface
{
    private TelegraphBot $telegraphBot;
    private TelegraphChat $telegraphChat;

    public function handleCommand(mixed $arguments = null): void
    {
        $this->telegraphChat->html('<b>Hoşgeldiniz!</b>')->send();
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
}
