<?php

namespace App\Http\Controllers;

use App\Enums\PrivacySettings;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Repositories\Profile\ProfileRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private readonly ProfileRepository $profileRepository)
    {
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'sessions' => DB::table('sessions')->where('user_id', $user->id)->get(),
            'privacySettings' => $user->privacySettings,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function show(User $user): View
    {
        return view('profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePrivacySettings(Request $request): RedirectResponse
    {
        return $this->profileRepository->updatePrivacySettings($request->user(), $request);
    }
}
