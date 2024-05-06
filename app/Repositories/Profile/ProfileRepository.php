<?php

namespace App\Repositories\Profile;

use App\Http\Requests\BiographyFormRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class ProfileRepository
{
    public function updateBiography(User $user, BiographyFormRequest $request): RedirectResponse
    {
        $user->update($request->validated());

        return redirect()->back()->with('success', 'Biography updated successfully');
    }
}
