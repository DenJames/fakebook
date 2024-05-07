<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum ProfileVisibilityTypes: int
{
    case PUBLIC = 1;
    case ONLY_FRIENDS = 2;
    case PRIVATE = 3;

    public function toUpperSnakeCase(): string
    {
        return Str::title(Str::replace('_', ' ', $this->name));
    }
}
