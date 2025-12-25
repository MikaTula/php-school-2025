<?php

declare(strict_types=1);

namespace App\Http\Validators;

use Carbon\Carbon;

class DateValidator implements IValidator
{
    public function isValid(string $fieldName, array $params): bool
    {
        $date = Carbon::createFromFormat('Y-m-d', $params[$fieldName]);
        return $date && $date->format('Y-m-d') === $params[$fieldName];
    }
}
