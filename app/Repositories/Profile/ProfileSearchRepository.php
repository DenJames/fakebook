<?php

namespace App\Repositories\Profile;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfileSearchRepository
{
    public function search(string $query, bool $paginate = false, int $perPage = 10): array|LengthAwarePaginator
    {
        $query = User::where('name', 'like', "%{$query}%");

        if ($paginate) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}
