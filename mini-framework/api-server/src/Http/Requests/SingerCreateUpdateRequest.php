<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Api\Models\SingerCreateUpdateModel;
use App\Http\Request;
use JsonMapper;
use JsonMapper_Exception;

class SingerCreateUpdateRequest extends Request
{
    public function rules(): array
    {
        return match ($this->method) {
            'GET', 'DELETE', 'PUT' => [
                'id' => ['required', 'int'],
                'name' => ['required', 'max:20']
            ],
            'POST' => [
                'name' => ['required', 'max:20']
            ],
            default => [],
        };
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function getModel(): SingerCreateUpdateModel
    {
        return new JsonMapper()->map((object)$this->data->params, new SingerCreateUpdateModel());
    }
}
