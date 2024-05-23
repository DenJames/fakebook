<?php

namespace App\Repositories\Support;

use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\Auth;

class TicketCategoryRepository
{
    public function fetchAll()
    {
        return TicketCategory::where('enabled', true)->get();
    }
}
