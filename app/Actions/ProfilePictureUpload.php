<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfilePictureUpload
{
    public function execute(User $user, UploadedFile $file): bool|string
    {
        $path = 'profile-photos/' . $user->email . '/' . $file->hashName();

        if (! Storage::disk('public')->put($path, $file->getContent())) {
            Log::error('Failed to upload profile photo for user: ' . $user->email);

            return false;
        }

        return $path;
    }
}
