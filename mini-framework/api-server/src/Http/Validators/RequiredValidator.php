<?php

namespace App\Http\Validators;

class RequiredValidator implements IValidator
{
    public function isValid(string $fieldName, array $params): bool
    {
        if (!array_key_exists($fieldName, $params)) {
            return false;
        }

        return $params[$fieldName] !== null;
    }
}
