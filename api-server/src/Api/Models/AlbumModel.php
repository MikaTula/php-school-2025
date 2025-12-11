<?php

    declare(strict_types=1);

    namespace App\Api\Models;

    class AlbumModel
    {
        public int $id;
        public ?string $name = null;
        public ?int $artistId = null;
        public ?int $year = null;
    }
