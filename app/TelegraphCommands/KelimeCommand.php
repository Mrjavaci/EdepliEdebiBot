<?php

namespace App\TelegraphCommands;

use App\Models\Words;
use DefStudio\Telegraph\Models\TelegraphChat;

class KelimeCommand implements \App\Interfaces\TelegraphCommandInterface
{
    private TelegraphChat $telegraphChat;

    public static function getDescription()
    {
        return 'Rastgele kelime ve etimolojisi içün.';
    }

    public function setChat(TelegraphChat $telegraphChat): self
    {
        $this->telegraphChat = $telegraphChat;
        return $this;
    }

    public function handleCommand(mixed $arguments = null): void
    {
        $word = Words::query()->inRandomOrder()->limit(1)->first();
        $this->telegraphChat->html(sprintf("Senin İçin Seçtiğimiz Kelime <b>%s</b>", $word->word))->send();
        if ($word->history) {
            $this->telegraphChat->html('Tarihçe: ' . $word->history)->send();
        }
        if ($word->origin) {
            $this->telegraphChat->html('Köken: ' . $word->origin)->send();
        }
        if ($word->annotation) {
            $this->telegraphChat->html('Ek Açıklama: ' . $word->annotation)->send();
        }


    }
}
