<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Api\Models\FileRenameModel;
use App\Http\Request;
use JsonMapper;
use JsonMapper_Exception;

class FileRenameRequest extends Request
{
    /**
     * @throws JsonMapper_Exception
     */
    public function getModel(): FileRenameModel
    {
        return new JsonMapper()->map((object)$this->data->params, new FileRenameModel());
    }

    public function rules(): array
    {
        return match ($this->method) {
            'POST' => [
                'id' => ['required'],
                'name' => ['required']
            ],
            default => [],
        };
    }
}
