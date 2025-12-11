<?php

    declare(strict_types=1);

    namespace App\Api\Controllers;

    use App\DataAccess\AlbumsRepository;
    use App\Http\Requests\AlbumRequest;
    use App\Http\Response;
    use App\Http\ResponseCode;

    class AlbumController extends BaseController
    {
        public function __construct(private readonly AlbumsRepository $albumsRepository) {}

        public function getAlbums(): Response
        {
            $albums = $this->albumsRepository->getAll();
            return $this->successResponse($albums);
        }

        public function getAlbum(AlbumRequest $request): Response
        {
            $body = $request->getModel();
            $album = $this->albumsRepository->getById($body->id);

            return ($album!==null) ? $this->successResponse($album):$this->failResponse(ResponseCode::NotFound,
                    'No such album', null);
        }

        public function deleteAlbum(AlbumRequest $request): Response
        {
            $body = $request->getModel();
            $this->albumsRepository->removeById($body->id);

            return $this->successResponse();
        }

        public function putAlbum(AlbumRequest $request): Response
        {
            $body = $request->getModel();
            $this->albumsRepository->put($body);

            return $this->successResponse();
        }

        public function postAlbum(AlbumRequest $request): Response
        {
            $body = $request->getModel();
            $this->albumsRepository->post($body);

            return $this->successResponse();
        }
    }
