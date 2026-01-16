<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Api\Models\LoginModel;
use App\Http\Request;
use JsonMapper;
use JsonMapper_Exception;

class LoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'login' => ['required', 'max:32'],
            'password' => ['required'],
        ];
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function getModel(): LoginModel
    {
        return new JsonMapper()->map((object)$this->data->params, new LoginModel());
    }
}
