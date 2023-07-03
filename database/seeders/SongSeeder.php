<?php

namespace Database\Seeders;

use App\Models\Songs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SongSeeder extends Seeder
{
    const FILE_NAME = 'gece-icin-sarkilar.json';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $songs = $this->getSongsJson();
        collect($songs)->each(function (string $songName) {
            Songs::query()->create([
                'name' => $songName
            ]);
        });
    }

    protected function getSongsJson(): array
    {
        if (Storage::disk('public')->exists(self::FILE_NAME)) {
            return json_decode($this->getSongsStorage(), 1);
        }
        $poems = file_get_contents(config('app.songsJsonFileUrl'));
        $this->setSongsStorage($poems);

        return json_decode($poems, 1);
    }

    /**
     * @return string|null
     */
    public function getSongsStorage(): ?string
    {
        return Storage::disk('public')->get(self::FILE_NAME);
    }

    /**
     * @param string $songs
     * @return void
     */
    public function setSongsStorage(string $songs): void
    {
        Storage::disk('public')->put(self::FILE_NAME, $songs);
    }

}
