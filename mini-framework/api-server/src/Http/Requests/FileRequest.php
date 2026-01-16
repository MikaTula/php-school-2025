<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Api\Models\FileModel;
use App\Http\Request;
use JsonMapper;
use JsonMapper_Exception;

class FileRequest extends Request
{
    public function rules(): array
    {
        return match ($this->method) {
            'GET', 'DELETE' => [
                'id' => ['required', 'int'],
            ],
            'PUT', 'POST' => [
                'file' => ['required']
            ],
            default => [],
        };
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function getModel(): FileModel
    {
        return new JsonMapper()->map((object)$this->data->params, new FileModel());
    }
}
