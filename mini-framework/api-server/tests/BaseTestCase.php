<?php

declare(strict_types=1);

namespace Tests;

use App\Api\Models\AlbumModel;
use App\Api\Models\GenreModel;
use App\Api\Models\SingerModel;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * @var AlbumModel[]
     */
    public array $albums = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->albums = [
            $this->generateAlbumModel(
                10,
                "Test album",
                $this->generateSingerModel(12, 'John Doe'),
                $this->generateGenreModel(1, 'Rock'),
                1998
            ),
            $this->generateAlbumModel(
                12,
                "Test album 12",
                $this->generateSingerModel(12, 'John Doe'),
                $this->generateGenreModel(2, 'Opera'),
                1998
            ),
            $this->generateAlbumModel(
                13,
                "Test album 14",
                $this->generateSingerModel(12, 'John Doe'),
                $this->generateGenreModel(3, 'jazz'),
                1998
            ),
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function generateAlbumModel(
        int $id,
        string $title,
        SingerModel $singer,
        GenreModel $genre,
        ?int $year = null,
    ): AlbumModel {
        $album = new AlbumModel();
        $album->id = $id;
        $album->title = $title;
        $album->singer = $singer;
        $album->genre = $genre;
        $album->year = $year;
        return $album;
    }

    private function generateSingerModel(
        int $id,
        string $name,
    ): SingerModel {
        $singer = new SingerModel();
        $singer->name = $name;
        $singer->id = $id;
        return $singer;
    }

    private function generateGenreModel(
        int $id,
        string $name,
    ): GenreModel {
        $singer = new GenreModel();
        $singer->name = $name;
        $singer->id = $id;
        return $singer;
    }
}
