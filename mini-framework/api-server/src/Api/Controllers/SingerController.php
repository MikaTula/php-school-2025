<?php

    declare(strict_types=1);

    namespace App\Api\Controllers;

    use App\DataAccess\Interfaces\SingerRepositoryInterface;
    use App\Http\Requests\AlbumCreateUpdateRequest;
    use App\Http\Requests\AlbumRequest;
    use App\Http\Requests\SingerCreateUpdateRequest;
    use App\Http\Requests\SingerRequest;
    use App\Http\Response;
    use App\Http\ResponseCode;

    class SingerController extends BaseController
    {
        public function __construct(private readonly SingerRepositoryInterface $artistRepository) {}

        public function getAll(): Response
        {
            $albums = $this->artistRepository->getAll();
            return $this->successResponse($albums);
        }

        public function getById(SingerRequest $request): Response
        {
            $body = $request->getModel();
            $artist = $this->artistRepository->getById($body->id);

            return ($artist!==null) ? $this->successResponse($artist):$this->failResponse(ResponseCode::NotFound,
                    'No such artist', null);
        }

        public function delete(SingerRequest $request): Response
        {
            $body = $request->getModel();
            $this->artistRepository->removeById($body->id);

            return $this->successResponse();
        }

        public function update(SingerCreateUpdateRequest $request): Response
        {
            $body = $request->getModel();
            $this->artistRepository->update($body);

            return $this->successResponse();
        }

        public function create(SingerCreateUpdateRequest $request): Response
        {
            $body = $request->getModel();
            $this->artistRepository->create($body);

            return $this->successResponse();
        }
    }
