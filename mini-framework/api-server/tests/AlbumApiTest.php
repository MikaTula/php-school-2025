<?php

declare(strict_types=1);

namespace Tests;

use App\Api\Controllers\AlbumController;
use App\DataAccess\Interfaces\AlbumsRepositoryInterface;
use App\Http\Requests\AlbumRequest;
use Error;
use PHPUnit\Framework\MockObject\Exception;
use stdClass;

class AlbumApiTest extends BaseTestCase
{
    /**
     * @throws Exception
     */
    public function testGetAll()
    {
        $mock = $this->createMock(AlbumsRepositoryInterface::class);
        $mock->method('getAll')->willReturn($this->albums);

        $albumController = new AlbumController($mock);
        $response = $albumController->getAll();

        $this->assertSameSize($this->albums, $response->data);
    }

    /**
     * @throws Exception
     */
    public function testGetById()
    {
        $mock = $this->createMock(AlbumsRepositoryInterface::class);
        $mock->method('getById')->with(12)->willReturn($this->albums[1]);

        $params = new stdClass();
        $params->params = ['id' => 12];

        $request = new AlbumRequest('GET', '/api/albums', $params);

        $albumController = new AlbumController($mock);
        $response = $albumController->getById($request);

        $this->assertEquals(12, $response->data->id);
        $this->assertIsObject($response->data);
    }

    /**
     * @throws Exception
     */
    public function testGetByIdNegative()
    {
        $mock = $this->createMock(AlbumsRepositoryInterface::class);

        $mock->method('getById')->with(12)->willThrowException(new Error("Album not found"));


        $albumController = new AlbumController($mock);

        $params = new stdClass();
        $params->params = ['id' => 12];

        $request = new AlbumRequest('GET', '/api/albums', $params);

        $this->expectException(Error::class);

        $this->expectExceptionMessage("Album not found");
        $albumController->getById($request);
    }
}
