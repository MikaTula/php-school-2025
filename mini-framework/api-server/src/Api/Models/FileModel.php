<?php

declare(strict_types=1);

namespace App\Api\Models;

use Carbon\Carbon;

class FileModel
{
    public int $id;
    public string $name;
    public string $path;
    public int $size;
    public string $type;
    public UserLightModel $createdBy;
    public Carbon $createdAt;
}
