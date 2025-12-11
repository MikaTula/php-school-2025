<?php

    declare(strict_types=1);

    namespace App\Domain;

    class Album extends Playlist
    {
        public int $id;

        public int $year;

        /**
         * @var Artist
         */
        public Artist $artist;

        public int $artistId;

    }
