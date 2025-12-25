<?php

declare(strict_types=1);

namespace App\Http\Validators;

class MaxLengthValidator implements IValidator
{
    private int $maxLength;

    public function __construct(int $length)
    {
        $this->maxLength = $length;
    }

    public function isValid(string $fieldName, array $params): bool
    {
        return strlen($params[$fieldName]) <= $this->maxLength;
    }
}
