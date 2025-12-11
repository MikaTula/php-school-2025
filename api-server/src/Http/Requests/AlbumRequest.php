<?php

    declare(strict_types=1);

    namespace App\Http\Requests;

    use App\Api\Models\AlbumModel;
    use App\Http\Request;
    use JsonMapper;

    class AlbumRequest extends Request
    {
        public function rules(): array
        {
            switch ($this->method) {
                case 'GET':
                case 'DELETE':
                case 'PUT':
                    return [
                            'id' => ['required', 'int']
                    ];

                case 'POST':
                    return [
                            'artistId' => ['required'], 'year' => ['required', 'int'], 'name' => ['required', 'max:20']
                    ];
            }

            return [];
        }

        public function getModel(): AlbumModel
        {
            return (new JsonMapper())->map((object) $this->data->params, new AlbumModel());
        }
    }
