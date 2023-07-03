<?php

namespace Database\Seeders;

use App\Models\Poems;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PoemsSeeder extends Seeder
{
    protected const FILE_NAME = 'PoemsJson.json';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = $this->getPoemJson();

        collect($json)->each(function (array $poem) {
            $body = $this->filterPoemBody(collect($poem['icerik']));
            try {
                Poems::query()->create([
                        'body' => $body->implode("\n"),
                        'poet' => $poem['sair'],
                        'header' => $poem['baslik']]
                );
            } catch (\Exception $exception) {
                dump('error', $exception->getMessage());
            }
        });

    }

    protected function getPoemJson(): array
    {
        if (Storage::disk('public')->exists(self::FILE_NAME)) {
            return json_decode($this->getPoemsStorage(), 1);
        }
        $poems = file_get_contents(config('app.poemsJsonFileUrl'));
        $this->setPoemsStorage($poems);

        return json_decode($poems, 1);
    }

    /**
     * @return string|null
     */
    public function getPoemsStorage(): ?string
    {
        return Storage::disk('public')->get(self::FILE_NAME);
    }

    /**
     * @param string $poems
     * @return void
     */
    public function setPoemsStorage(string $poems): void
    {
        Storage::disk('public')->put(self::FILE_NAME, $poems);
    }

    protected function filterPoemBody(Collection $icerik)
    {
        $filteredCollection = collect();
        $previousItemIsEmpty = false;
        if (empty($icerik[0])) {
            unset($icerik[0]);
        }
        foreach ($icerik as $item) {
            if (!empty($item)) {
                if ($previousItemIsEmpty) {
                    $filteredCollection->push('');
                }
                $filteredCollection->push($item);
                $previousItemIsEmpty = false;
            } else {
                $previousItemIsEmpty = true;
            }
        }
        return $filteredCollection;
    }
}
