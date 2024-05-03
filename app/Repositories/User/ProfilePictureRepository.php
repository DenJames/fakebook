<?php

namespace App\Repositories\User;

use App\Actions\ProfilePictureUpload;
use App\Http\Requests\ProfilePictureFormRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;


readonly class ProfilePictureRepository
{
    public function __construct(private ProfilePictureUpload $profilePictureUpload)
    {
    }

    public function uploadProfilePicture(User $user, ProfilePictureFormRequest $request): RedirectResponse
    {
        if (! $request->hasFile('profile_picture')) {
            return redirect()->back()->withErrors('No file was uploaded');
        }

        // If the profile picture upload fails, return an error message
        if (! $image = $this->profilePictureUpload->execute($user, $request->file('profile_picture'))) {
            return redirect()->back()->withErrors('Failed to upload profile picture');
        }

        // Set the previous profile photo to false if one exists
        $user->currentProfilePhoto()?->update([
            'is_current' => false,
        ]);

        if (! empty($image) && $image['image_id'] !== null) {
            $image = $user->profilePhotos()->where('id', $image['image_id'])->first();

            $image?->update([
                'is_current' => true,
            ]);

            return redirect()->back()->with('success', 'Profile picture uploaded successfully');
        }

        $user->profilePhotos()->create([
            'path' => $image['file_path'],
            'file_hash' => $image['file_hash'],
            'is_current' => true
        ]);

        return redirect()->back()->with('success', 'Profile picture uploaded successfully');
    }
}
