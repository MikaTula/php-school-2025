<?php

    declare(strict_types=1);

    namespace App\Domain;

    class Playlist
    {
        public int $id;

        public string $title;

        public int $numberSubscribers;

        /**
         * @var Song[]
         */
        public array $songsList;
    }
