<?php

namespace App\Repositories\Support;

use App\Models\TicketCategory;

class TicketCategoryRepository
{
    public function fetchAll()
    {
        return TicketCategory::where('enabled', true)->get();
    }
}
