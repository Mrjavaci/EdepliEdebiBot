<?php

namespace App\TelegraphCommands;

use App\Models\Poems;
use DefStudio\Telegraph\Models\TelegraphChat;

class SiirCommand implements \App\Interfaces\TelegraphCommandInterface
{
    private TelegraphChat $telegraphChat;

    public static function getDescription()
    {
        return 'Rastgele şiir okumak içün.';
    }

    public function setChat(TelegraphChat $telegraphChat): self
    {
        $this->telegraphChat = $telegraphChat;
        return $this;
    }

    public function handleCommand(mixed $arguments = null): void
    {
        $poem = Poems::query()->inRandomOrder()->limit(1)->first();
        $this->telegraphChat->html('Senin İçin Seçtiğimiz Şiir; <b>' . $poem->header . '</b>')->send();
        $this->telegraphChat->html(strip_tags($poem->body))->send();
        $this->telegraphChat->html('<b>-</b>' . $poem->poet)->send();
    }
}
