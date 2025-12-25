<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Genre;
use App\Domain\Playlist;
use Codemastersintl\MusicService\Models\Artist\Artist;

class UserDisplay
{
    public int $id;

    /**
     * @var Artist[]
     */
    public array $subscriptionsList;

    public Playlist $favouriteSongsList;

    /**
     * @var Playlist[]
     */
    public array $playlistsList;

    /**
     * @var Genre[]
     */
    public array $favouriteGenresList;
}
