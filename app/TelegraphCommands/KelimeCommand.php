<?php

namespace App\TelegraphCommands;

use App\Models\Words;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class KelimeCommand implements \App\Interfaces\TelegraphCommandInterface
{
    protected $arguments = null;
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
        $this->arguments = $arguments;
        if (is_null($arguments) || $arguments === '/kelime') {
            $this->sendRandomWord();
            return;
        }

        $this->searchWord($arguments);
    }

    protected function sendRandomWord()
    {
        $word = Words::query()->inRandomOrder()->limit(1)->first();
        $this->sendWord($word);
    }

    protected function sendWord(Words|Collection|null $word): void
    {
        if (is_null($word) || $word->count() === 0) {
            $this->telegraphChat->html('Aranılan kelime bulunamamıştır.')->send();
            return;
        }
        $allWords = $word;
        $word = $word->count() === 1 ? $word->first() : $word;
        try {
            if ($this->arguments === '/kelime') {
                $this->telegraphChat->html(sprintf("Senin İçin Rastgele Bulduğumuz Kelime <b>%s</b>", $word->word))->send();
            } else {
                $this->telegraphChat->html(sprintf("Senin İçin Aradığımız Kelime <b>%s</b>", $word->word))->send();
            }
        } catch (\Exception $e) {
            $this->telegraphChat->html('Aranılan kelime bulunamamıştır.')->send();
            return;
        }
        if (!empty($word->history)) {
            $this->telegraphChat->html('Tarihçe: ' . strip_tags($word->history))->send();
        }
        if (!empty($word->origin)) {
            $this->telegraphChat->html('Köken: ' . strip_tags($word->origin))->send();
        }
        if (!empty($word->annotation)) {
            $this->telegraphChat->html('Ek Açıklama: ' . strip_tags($word->annotation))->send();
        }
        $this->telegraphChat->html('Kelime aramak için "/kelime kelimeadi" olarak kullanabilirsiniz.')->send();

//        if ($allWords->count() > 1) {
//            $this->telegraphChat->html('Bulunan diğer kelimeleri "/kelime kelimeadi" olarak aratabilirsiniz. bulunan diğer kelimeler, ' . $allWords->map(fn($word) => $word->word)->join(', '))->send();
//        }
    }

    protected function searchWord(mixed $arguments)
    {
        $word = Words::query()->where('word', 'LIKE', '%' . mb_strtolower($arguments) . '%')->get();
        $this->sendWord($word);
    }
}
