<?php

    declare(strict_types=1);

    namespace App\Http\Requests;

    use App\Api\Models\AlbumCreateUpdateModel;
    use App\Api\Models\SingerCreateUpdateModel;
    use App\Http\Request;
    use JsonMapper;

    class AlbumCreateUpdateRequest extends Request
    {
        public function rules(): array
        {
            switch ($this->method) {
                case 'GET':
                case 'DELETE':
                case 'PUT':
                    return [
                        'id' => ['required', 'int'],
                        'year' => ['required', 'int'],
                        'title' => ['required', 'max:20']
                    ];

                case 'POST':
                    return [
                        'singerId' => ['required'],
                        'year' => ['required', 'int'],
                        'title' => ['required', 'max:20']
                    ];
            }

            return [];
        }

        public function getModel(): AlbumCreateUpdateModel
        {
            return (new JsonMapper())->map((object)$this->data->params, new AlbumCreateUpdateModel());
        }
    }
