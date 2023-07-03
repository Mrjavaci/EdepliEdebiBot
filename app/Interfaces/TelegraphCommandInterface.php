<?php

namespace App\Interfaces;

use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;

interface TelegraphCommandInterface
{
    public function handleCommand(mixed $arguments = null): void;

    public function setBot(TelegraphBot $telegraphBot): self;

    public function setChat(TelegraphChat $telegraphChat): self;
}
