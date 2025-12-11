<?php

    declare(strict_types=1);

    namespace App\Domain;

    use App\Domain\User\UserDisplay;

    class Artist
    {
        public int $id;

        public int $numberSubscribers;
        /**
         * @var Playlist[]
         */
        public array $albumsList;
        /**
         * @var Song[]
         */
        public array $songsList;
        /**
         * @var UserDisplay[]
         */
        private array $subscribersList;
    }
