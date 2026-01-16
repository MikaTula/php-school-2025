<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Api\Models\FileCreateUpdateModel;
use App\Http\Request;
use JsonMapper;
use JsonMapper_Exception;

class FileCreateUpdateRequest extends Request
{
    public function rules(): array
    {
        return match ($this->method) {
            'GET', 'DELETE', 'PUT' => [
                'id' => ['required', 'int'],
                'name' => ['required', 'max:255']
            ],
            'POST' => [
                'file' => ['required']
            ],
            default => [],
        };
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function getModel(): FileCreateUpdateModel
    {
        return new JsonMapper()->map((object)$this->data->params, new FileCreateUpdateModel());
    }
}
