<?php

declare(strict_types=1);

namespace App\Api\Models;

class AlbumModel
{
    public int $id;
    public string $title;
    public ?int $year = null;
    public SingerModel $singer;
    public GenreModel $genre;
}
