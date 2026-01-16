<?php

declare(strict_types=1);

namespace App\Services;

use App\Api\Models\FileRenameModel;
use App\DataAccess\Interfaces\FilesRepositoryInterface;
use App\Domain\File;
use Exception;

class FileService
{
    /**
     * @throws Exception
     */
    public function __construct(
        private string $folder,
        private readonly FilesRepositoryInterface $repository
    ) {
        $this->folder = rtrim($folder, '/');
        if (!is_dir($this->folder)) {
            $this->ensureDirectory($folder);
        }
    }

    /**
     * @throws Exception
     */
    public function create(int $userID, $file): void
    {
        if (file_exists($file['tmp_name'])) {
            $saveName = $this->makeName($this->getExt($file['name']));
            $fullPath = $this->folder . '/' . $saveName;
            move_uploaded_file($file['tmp_name'], $fullPath);

            $createModel = new File();
            $createModel->createdBy = $userID;
            $createModel->path = $fullPath;
            $createModel->name = $file['name'];
            $createModel->type = $file['type'];
            $createModel->size = $file['size'];

            $this->repository->create($createModel);
        } else {
            throw new Exception('Has no file in post array');
        }
    }



    /**
     * @throws Exception
     */
    public function removeById(int $userID, int $fileId): void
    {
        $file = $this->repository->getById($fileId);

        if ($file !== null) {
            if ($file->createdBy->id === $userID) {
                unlink($file->path);
                $this->repository->removeById($fileId);
            } else {
                throw new Exception('Only owner can delete files');
            }
        } else {
            throw new Exception('File Not found');
        }
    }

    /**
     * @throws Exception
     */
    public function rename(int $userId, FileRenameModel $fileRenameModel): void
    {
        $file = $this->repository->getById($fileRenameModel->id);

        if ($file !== null) {
            if ($file->createdBy->id === $userId) {
                $this->repository->rename($fileRenameModel);
            } else {
                throw new Exception('Only owner can delete files');
            }
        } else {
            throw new Exception('File Not found');
        }
    }

    /**
     * @throws Exception
     */
    public function getStream(int $fileId): void
    {
        $file = $this->repository->getById($fileId);

        if ($file !== null) {
            $this->fileToStream($file->path, $file->type);
        } else {
            throw new Exception('File Not found');
        }
    }

    public function getDownload(int $fileId): void
    {
        $file = $this->repository->getById($fileId);

        if ($file !== null) {
            $this->fileToDownload($file->path, $file->name);
        } else {
            throw new Exception('File Not found');
        }
    }

    private function makeName(string $extension): string
    {
        do {
            $newName = md5((string)rand(10, 9999999)) . '.' . $extension;
        } while (file_exists(rtrim('/', $this->folder) . '/' . $newName));
        return $newName;
    }

    /**
     * @throws Exception
     */
    private function ensureDirectory(...$paths): void
    {
        foreach ($paths as $path) {
            $catalog = explode('/', $path);
            array_pop($catalog);
            $catalog = implode('/', $catalog);
            if (!is_dir($catalog)) {
                $this->createDirectory($catalog);
            }
        }
    }

    /**
     * Создает директорию по указанному пути, включая вложенные папки.
     *
     * @param  string  $directoryPath
     * @return void
     * @throws Exception
     */
    private function createDirectory(string $directoryPath): void
    {
        if (is_dir($directoryPath)) {
            return;
        }

        $parentDirectory = dirname($directoryPath);
        if (!is_dir($parentDirectory)) {
            $this->createDirectory($parentDirectory);
        }

        // Создаем целевую директорию
        if (!mkdir($directoryPath, 0755, true)) {
            throw new Exception("Не удалось создать директорию: $directoryPath");
        }
    }

    private function getExt($filename): string
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    private function fileToStream(string $path, string $type): void
    {
        if (file_exists($path)) {
                // сбрасываем буфер
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $type);
            // header('Content-Disposition: inline; filename='.basename($path));
            header('Content-Disposition: inline; filename=' . basename($path));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            // читаем файл и отправляем его пользователю
            readfile($path);
            exit;
        }
    }

    private function fileToDownload($path, string $prettyName): void
    {
        if (file_exists($path)) {
            // сбрасываем буфер
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($prettyName));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            // читаем файл и отправляем его пользователю
            readfile($path);
            exit;
        }
    }
}
