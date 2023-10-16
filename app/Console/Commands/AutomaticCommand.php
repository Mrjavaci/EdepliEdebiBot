<?php

namespace App\Console\Commands;

use App\Models\Ignores;
use App\Models\Poems;
use App\Models\Songs;
use App\Models\Words;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Console\Command;

class AutomaticCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:automatic-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        TelegraphChat::all()->each(function (TelegraphChat $chat) {
            if (Ignores::query()->where('chat_id', $chat->chat_id)->exists()) {
                return;
            }

            $this->sendRandomWord($chat);
            $chat->html('<b>**********</b>');
            $this->sendRandomSong($chat);
            $chat->html('<b>**********</b>');
            $this->sendRandomPoem($chat);

        })->filter();

    }

    protected function sendRandomWord(TelegraphChat $chat)
    {
        $word = Words::query()->inRandomOrder()->limit(1)->first();
        $chat->html(sprintf("Senin İçin Rastgele Bulduğumuz Kelime <b>%s</b>", $word->word))->send();
        $chat->html('Tarihçe: ' . strip_tags($word->history))->send();
        $chat->html('Köken: ' . strip_tags($word->origin))->send();
        $chat->html('Ek Açıklama: ' . strip_tags($word->annotation))->send();
    }

    protected function sendRandomSong(TelegraphChat $chat)
    {
        $chat->html(sprintf("Senin İçin Seçtiğimiz Musiki Eser;%s<b>%s</b>", PHP_EOL, Songs::query()->inRandomOrder()->limit(1)->first()->name))->send();

    }

    protected function sendRandomPoem(TelegraphChat $chat)
    {
        $poem = Poems::query()->inRandomOrder()->limit(1)->first();
        $chat->html('Senin İçin Seçtiğimiz Şiir; <b>' . $poem->header . '</b>')->send();
        $chat->html(strip_tags($poem->body))->send();
        $chat->html('<b>-</b>' . $poem->poet)->send();
    }

}
