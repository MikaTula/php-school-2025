<?php

declare(strict_types=1);

namespace App\Http\Validators;

interface IValidator
{
    public function isValid(string $fieldName, array $params): bool;
}
