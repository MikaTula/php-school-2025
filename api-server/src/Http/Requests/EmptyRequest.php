<?php

    declare(strict_types=1);

    namespace App\Http\Requests;

    use App\Http\Request;

    class EmptyRequest extends Request
    {
        public function rules(): array
        {
            return [];
        }

        public function getModel(): object
        {
            return (object) $this->data->params;
        }
    }
