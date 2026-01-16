<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\DataAccess\Interfaces\SingerRepositoryInterface;
use App\Http\Requests\SingerCreateUpdateRequest;
use App\Http\Requests\SingerRequest;
use App\Http\Response;
use JsonMapper_Exception;

class SingerController extends BaseController
{
    public function __construct(private readonly SingerRepositoryInterface $singerRepository)
    {
    }

    public function getAll(): Response
    {
        $albums = $this->singerRepository->getAll();
        return $this->successResponse($albums);
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function getById(SingerRequest $request): Response
    {
        $body = $request->getModel();
        $singer = $this->singerRepository->getById($body->id);

        return $this->successResponse($singer);
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function delete(SingerRequest $request): Response
    {
        $body = $request->getModel();
        $this->singerRepository->removeById($body->id);

        return $this->successResponse();
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function update(SingerCreateUpdateRequest $request): Response
    {
        $body = $request->getModel();
        $this->singerRepository->update($body);

        return $this->successResponse();
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function create(SingerCreateUpdateRequest $request): Response
    {
        $body = $request->getModel();
        $this->singerRepository->create($body);

        return $this->successResponse();
    }
}
