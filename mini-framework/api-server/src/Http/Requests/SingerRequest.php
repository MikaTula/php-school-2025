<?php

    declare(strict_types=1);

    namespace App\Http\Requests;

    use App\Api\Models\SingerModel;
    use App\Http\Request;
    use JsonMapper;

    class SingerRequest extends Request
    {
        public function rules(): array
        {
            switch ($this->method) {
                case 'GET':
                case 'DELETE':
                    return [
                        'id' => ['required', 'int'],
                    ];
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

        public function getModel(): SingerModel
        {
            return (new JsonMapper())->map((object)$this->data->params, new SingerModel());
        }
    }
