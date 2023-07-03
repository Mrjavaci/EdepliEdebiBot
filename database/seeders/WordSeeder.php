<?php

namespace Database\Seeders;

use App\Models\Words;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class WordSeeder extends Seeder
{

    const FILE_NAME = 'words.json';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $words = $this->getWordsJson();
        collect($words)->each(function (array $word) {
            Words::query()->create([
                'word' => $word['baslik'],
                'history' => $word['tarihce'],
                'origin' => $word['koken'],
                'annotation' => $word['ek_aciklama'],
            ]);
        });
    }

    protected function getWordsJson(): array
    {
        if (Storage::disk('public')->exists(self::FILE_NAME)) {
            return json_decode($this->getWordsStorage(), 1);
        }
        $poems = file_get_contents(config('app.wordJsonFileUrl'));
        $this->setWordsStorage($poems);

        return json_decode($poems, 1);
    }

    /**
     * @return string|null
     */
    public function getWordsStorage(): ?string
    {
        return Storage::disk('public')->get(self::FILE_NAME);
    }

    /**
     * @param string $songs
     * @return void
     */
    public function setWordsStorage(string $songs): void
    {
        Storage::disk('public')->put(self::FILE_NAME, $songs);
    }
}
