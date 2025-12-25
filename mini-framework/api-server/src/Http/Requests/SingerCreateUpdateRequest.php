<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Api\Models\SingerCreateUpdateModel;
use App\Http\Request;
use JsonMapper;

class SingerCreateUpdateRequest extends Request
{
    public function rules(): array
    {
        switch ($this->method) {
            case 'GET':
            case 'DELETE':
            case 'PUT':
                return [
                    'id' => ['required', 'int'],
                    'name' => ['required', 'max:20']
                ];

            case 'POST':
                return [
                    'name' => ['required', 'max:20']
                ];
        }

        return [];
    }

    public function getModel(): SingerCreateUpdateModel
    {
        return (new JsonMapper())->map((object)$this->data->params, new SingerCreateUpdateModel());
    }
}
