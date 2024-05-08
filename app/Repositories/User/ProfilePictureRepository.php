<?php

namespace App\Repositories\User;

use App\Actions\ProfilePictureUpload;
use App\Enums\UserProfilePhotoTypes;
use App\Http\Requests\ProfilePictureFormRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;


readonly class ProfilePictureRepository
{
    public function __construct(private ProfilePictureUpload $profilePictureUpload)
    {
    }

    public function upload(User $user, ProfilePictureFormRequest $request, $storagePath = 'profile-photos'): JsonResponse|RedirectResponse
    {
        if (!$user->isUserProfile()) {
            $message = 'You do not have permissions for this';
            return $request->wantsJson()
                ? response()->json(['error' => $message], 403)
                : redirect()->back()->withErrors($message);
        }

        if (! $request->hasFile('photo')) {
            $message = 'No file was uploaded';
            return $request->wantsJson()
                ? response()->json(['error' => $message], 400)
                : redirect()->back()->withErrors($message);
        }

        // If the profile picture upload fails, return an error message
        if (! $image = $this->profilePictureUpload->execute($user, $request->file('photo'), $storagePath)) {
            $message = 'Failed to upload profile picture';
            return $request->wantsJson()
                ? response()->json(['error' => $message], 500)
                : redirect()->back()->withErrors($message);
        }
        if ($storagePath === 'cover-photos') {
            $user->currentCoverPhoto()?->update([
                'is_current' => false,
            ]);
        } else {
            $user->currentProfilePhoto()?->update([
                'is_current' => false,
            ]);
        }

        $type = $storagePath === 'cover-photos' ? UserProfilePhotoTypes::COVER_PHOTO->value : UserProfilePhotoTypes::PROFILE_PHOTO->value;
        if (! empty($image) && $image['image_id'] !== null) {
            $image = $user->profilePhotos()->where('id', $image['image_id'])->where('type', $type)->first();

            $image?->update([
                'is_current' => true,
            ]);


            $message = 'The image has been uploaded successfully';
            return $request->wantsJson()
                ? response()->json(['success' => $message])
                : redirect()->back()->with('success', $message);
        }

        $user->profilePhotos()->create([
            'type' => $type,
            'path' => $image['file_path'],
            'file_hash' => $image['file_hash'],
            'is_current' => true
        ]);

        $message = 'The image has been uploaded successfully';
        return $request->wantsJson()
            ? response()->json(['success' => $message])
            : redirect()->back()->with('success', $message);
    }
}
