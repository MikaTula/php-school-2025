<?php

declare(strict_types=1);

namespace App\Domain;

use Carbon\Carbon;

class File
{
    public int $id;
    public string $name;
    public string $path;
    public string $type;
    public int $size;
    public int $createdBy;
    public Carbon $createdAt;
}
