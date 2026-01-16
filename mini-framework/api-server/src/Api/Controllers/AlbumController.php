<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\DataAccess\Interfaces\AlbumsRepositoryInterface;
use App\Http\Requests\AlbumCreateUpdateRequest;
use App\Http\Requests\AlbumRequest;
use App\Http\Response;
use App\Http\ResponseCode;
use App\Services\CacheService;
use JsonMapper_Exception;

class AlbumController extends BaseController
{
    /**
     * @param AlbumsRepositoryInterface $albumsRepository
     * @param CacheService $cacheService
     */
    public function __construct(
        private readonly AlbumsRepositoryInterface $albumsRepository,
        private readonly CacheService $cacheService
    ) {
    }

    public function getAll(): Response
    {
        $albums = $this->albumsRepository->getAll();
        return $this->successResponse($albums);
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function getById(AlbumRequest $request): Response
    {
        $body = $request->getModel();
        // $album = $this->albumsRepository->getById($body->id);

        $album = $this->cacheService->getOrSave(
            $this->cacheService->makeAlbumKey($body->id),
            fn() => $this->albumsRepository->getById($body->id)
        );

        return ($album !== null) ? $this->successResponse($album) : $this->failResponse(
            ResponseCode::NotFound,
            'No such album',
            null
        );
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function delete(AlbumRequest $request): Response
    {
        $body = $request->getModel();
        $this->albumsRepository->removeById($body->id);

        return $this->successResponse();
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function update(AlbumCreateUpdateRequest $request): Response
    {
        $body = $request->getModel();
        $this->albumsRepository->update($body);

        return $this->successResponse();
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function create(AlbumCreateUpdateRequest $request): Response
    {
        $body = $request->getModel();
        $this->albumsRepository->create($body);

        return $this->successResponse();
    }
}
