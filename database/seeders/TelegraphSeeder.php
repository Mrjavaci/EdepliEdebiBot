<?php

namespace Database\Seeders;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Database\Seeder;

class TelegraphSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bot = TelegraphBot::create([
            'name' => env('TELEGRAM_BOT_NAME'),
            'token' => env('TELEGRAM_BOT_TOKEN'),
        ]);
        $bot->registerWebhook()->send();

    }
}
