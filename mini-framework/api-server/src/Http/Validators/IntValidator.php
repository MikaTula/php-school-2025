<?php

namespace App\Http\Validators;

class IntValidator implements IValidator
{
    public function isValid(string $fieldName, array $params): bool
    {
        $fieldValue = $params[$fieldName];

        if (!is_numeric($fieldValue)) {
            return false;
        }

        $int = (int)$fieldValue;
        $float = (float)$fieldValue;

        return $int == $float;
    }
}
