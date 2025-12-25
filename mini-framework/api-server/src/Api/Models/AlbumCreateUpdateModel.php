<?php

declare(strict_types=1);

namespace App\Api\Models;

class AlbumCreateUpdateModel
{
    public int $id;
    public string $title;
    public int $singerId;
    public ?int $year = null;
}
