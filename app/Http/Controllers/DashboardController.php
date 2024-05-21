<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'posts' => Post::query()
                ->whereIn('user_id', array_merge(Auth::user()->friendsArray, [Auth::user()->id]))
                ->orderByDesc('id')
                ->get(),
        ]);
    }
}
