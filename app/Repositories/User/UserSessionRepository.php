<?php

namespace App\Repositories\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSessionRepository
{
    public function logoutOtherDevices(Request $request): void
    {
        $request->validateWithBag('logoutSessions', [
            'password' => ['required', 'current_password'],
        ]);

        DB::table('sessions')->where('user_id', $request->user()->id)
            ->where('id', '!=', $request->session()->getId())
            ->delete();
    }
}
