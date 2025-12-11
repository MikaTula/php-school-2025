<?php

    declare(strict_types=1);

    namespace App\Domain;

    class Song
    {
        public int $id;

        public string $title;

        /**
         * @var Artist[]
         */
        public array $artistsList;

        public int $numberLikes;

        /**
         * @var Genre[]
         */
        public array $genresList;
    }
