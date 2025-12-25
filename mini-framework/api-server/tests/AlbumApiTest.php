<?php

    use App\Api\Controllers\AlbumController;
    use App\DataAccess\AlbumsRepository;
    use App\Http\Requests\AlbumRequest;
    use PHPUnit\Framework\TestCase;

    require_once(__DIR__.'/../vendor/autoload.php');


    class AlbumApiTest extends TestCase
    {
        private array $albums = [
                ["id" => 1, "name" => "Test Name 1", "artistId" => 1, "year" => 2002],
                ["id" => 2, "name" => "Test Name 2", "artistId" => 1, "year" => 2024],
                ["id" => 3, "name" => "Test Name 3", "artistId" => 2, "year" => 2024]
        ];


        public function test_GetAll()
        {
            // $albumsRepository = new AlbumsRepository(dirname(__DIR__) . '/resources/input/albums.txt');

            $mock = $this->createMock(AlbumsRepository::class);
            $mock->method('getAll')->willReturn($this->albums);

            $albumController = new AlbumController($mock);

            $response = $albumController->getAlbums();
            $this->assertEquals(count($this->albums), count($response->data));
        }

        public function test_GetById()
        {
            // $albumsRepository = new AlbumsRepository(dirname(__DIR__) . '/resources/input/albums.txt');

            $mock = $this->createMock(AlbumsRepository::class);
            $mock->method('getById')->with(2)->willReturn($this->albums[1]);

            $albumController = new AlbumController($mock);

            $params = new stdClass();
            $params->params = ['id' => 2];

            $request = new AlbumRequest('GET', '/api/albums', $params);

            $response = $albumController->getAlbum($request);

            $this->assertEquals(2, $response->data['id']);
            $this->assertIsArray($response->data);
        }

        public function test_GetById_negative()
        {
            $mock = new AlbumsRepository(dirname(__DIR__).'/resources/input/albums.txt');

            // $mock = $this->createMock(AlbumsRepository::class);
            // $mock->method('getById')->with(10)->willReturn($this->albums[1]);

            $albumController = new AlbumController($mock);

            $params = new stdClass();
            $params->params = ['id' => 10];

            $request = new AlbumRequest('GET', '/api/albums', $params);

            $response = $albumController->getAlbum($request);

            $this->assertNull($response->data);
            $this->assertEquals(404, $response->code);
        }

    }
