<?php

    declare(strict_types=1);

    namespace App\DataAccess;

    use App\Api\Models\AlbumModel;
    use App\Utils\DataUtils;

    class AlbumsRepository
    {
        private array $albums;

        public function __construct(string $filePath)
        {
            $this->albums = DataUtils::readDataSource($filePath);
        }

        public function getAll(): ?array
        {
            return $this->albums;
        }

        public function getById(int $albumId): ?array
        {
            foreach ($this->albums as $album) {
                if ($album['id']===$albumId) {
                    return $album;
                }
            }
            return null;
        }

        public function removeById(int $albumId)
        {
            foreach ($this->albums as $key => $album) {
                if ($album['id']===$albumId) {
                    unset($this->albums[$key]);
                }
            }

            self::update($this->albums);
        }

        public function put(AlbumModel $albumModel)
        {
            foreach ($this->albums as $key => $album) {
                if ($album['id']===$albumModel->id) {
                    foreach ($albumModel as $param => $value) {
                        if ($value!==null) {
                            $this->albums[$key][$param] = $value;
                        }
                    }
                    break;
                }
            }

            self::update($this->albums);
        }

        public function post(AlbumModel $album)
        {
            $this->albums[] = [
                    'id'       => (int) end($this->albums)['id'] + 1, 'name' => $album->name,
                    'artistId' => $album->artistId, 'year' => $album->year
            ];

            self::update($this->albums);
        }

        private static function update(mixed $data)
        {
            DataUtils::writeDataSource(dirname(__DIR__).'/../resources/input/albums.txt', $data);
        }
    }
