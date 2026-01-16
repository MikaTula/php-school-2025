<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\DataAccess\Interfaces\FilesRepositoryInterface;
use App\Http\Requests\FileCreateUpdateRequest;
use App\Http\Requests\FileRenameRequest;
use App\Http\Requests\FileRequest;
use App\Http\Response;
use App\Http\ResponseCode;
use App\Services\FileService;
use Exception;
use JsonMapper_Exception;

class FileController extends BaseController
{
    public function __construct(
        private readonly FilesRepositoryInterface $fileRepository,
        private readonly FileService $fileService
    ) {
    }

    /**
     * @throws Exception
     */
    public function create(FileCreateUpdateRequest $request): Response
    {
        $body = $request->getModel();
        $this->fileService->create($request->authInfo->userId, $body->file);

        return $this->successResponse();
    }

    /**
     * @throws JsonMapper_Exception
     * @throws Exception
     */
    public function delete(FileRequest $request): Response
    {
        $body = $request->getModel();
        $this->fileService->removeById($request->authInfo->userId, $body->id);

        return $this->successResponse();
    }

    public function getAll(): Response
    {
        $albums = $this->fileRepository->getAll();
        return $this->successResponse($albums);
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function getById(FileRequest $request): Response
    {
        $body = $request->getModel();
        $file = $this->fileRepository->getById($body->id);

        return ($file !== null) ? $this->successResponse($file) : $this->failResponse(
            ResponseCode::NotFound,
            'No such artist',
            null
        );
    }

    /**
     * @throws JsonMapper_Exception
     * @throws Exception
     */
    public function getDownload(FileRequest $request): void
    {
        $body = $request->getModel();
        $this->fileService->getDownload($body->id);
    }

    /**
     * @throws JsonMapper_Exception
     * @throws Exception
     */
    public function getStream(FileRequest $request): Response
    {
        $body = $request->getModel();
        $this->fileService->getStream($body->id);

        return $this->successResponse();
    }

    /**
     * @throws JsonMapper_Exception
     * @throws Exception
     */
    public function rename(FileRenameRequest $request): Response
    {
        $body = $request->getModel();
        $this->fileService->rename($request->authInfo->userId, $body);

        return $this->successResponse();
    }
}
