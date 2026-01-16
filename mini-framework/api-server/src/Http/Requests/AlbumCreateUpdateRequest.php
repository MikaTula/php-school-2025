<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Api\Models\AlbumCreateUpdateModel;
use App\Http\Request;
use JsonMapper;
use JsonMapper_Exception;

class AlbumCreateUpdateRequest extends Request
{
    public function rules(): array
    {
        return match ($this->method) {
            'GET', 'DELETE', 'PUT' => [
                'id' => ['required', 'int'],
                'year' => ['required', 'int'],
                'title' => ['required', 'max:20']
            ],
            'POST' => [
                'singerId' => ['required'],
                'year' => ['required', 'int'],
                'title' => ['required', 'max:20']
            ],
            default => [],
        };
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function getModel(): AlbumCreateUpdateModel
    {
        return new JsonMapper()->map((object)$this->data->params, new AlbumCreateUpdateModel());
    }
}
