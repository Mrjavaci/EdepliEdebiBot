<?php

namespace App\TelegraphCommands;

use App\Models\Songs;
use DefStudio\Telegraph\Models\TelegraphChat;

class MuzikCommand implements \App\Interfaces\TelegraphCommandInterface
{
    private TelegraphChat $telegraphChat;

    public static function getDescription()
    {
        return 'Rastgele müzik ismi öğrenmek içün.';
    }

    public function setChat(TelegraphChat $telegraphChat): self
    {
        $this->telegraphChat = $telegraphChat;
        return $this;
    }

    public function handleCommand(mixed $arguments = null): void
    {
        $this->telegraphChat->html(sprintf("Senin İçin Seçtiğimiz Musiki Eser;<b>%s</b>", Songs::query()->inRandomOrder()->limit(1)->first()->name))->send();
    }
}
