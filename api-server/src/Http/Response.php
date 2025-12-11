<?php

    declare(strict_types=1);

    namespace App\Http;

    class Response
    {
        public function __construct(
                public int $code,
                public mixed $data,
                public ?string $message = null
        ) {}
    }
