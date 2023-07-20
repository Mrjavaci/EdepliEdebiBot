<?php

namespace App\TelegraphCommands;

use App\Models\Words;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Log;

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
        if (is_null($arguments)) {
            $this->sendRandomWord();
        }

        $this->searchWord($arguments);
    }

    protected function sendRandomWord()
    {
        $word = Words::query()->inRandomOrder()->limit(1)->first();
        $this->sendWord($word);
    }

    protected function sendWord($word): void
    {
        if (count($word) === 0) {
            $this->telegraphChat->html('Aranılan kelime bulunamamıştır.')->send();
            return;
        }
        $this->telegraphChat->html(sprintf("Senin İçin Seçtiğimiz Kelime <b>%s</b>", $word->word))->send();
        if (!empty($word->history)) {
            $this->telegraphChat->html('Tarihçe: ' . strip_tags($word->history))->send();
        }
        if (!empty($word->origin)) {
            $this->telegraphChat->html('Köken: ' . strip_tags($word->origin))->send();
        }
        if (!empty($word->annotation)) {
            $this->telegraphChat->html('Ek Açıklama: ' . strip_tags($word->annotation))->send();
        }
    }

    protected function searchWord(mixed $arguments)
    {
        Log::debug('arguments' . $arguments);
        $word = Words::query()->where('word', 'LIKE', '%' . $arguments . '%')->get();
        $this->sendWord($word);
    }
}
