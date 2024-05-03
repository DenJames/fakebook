<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfilePictureUpload
{
    public function execute(User $user, UploadedFile $file): bool|array
    {
        $fileHash = md5($file->getContent());

        // If the user has previously uploaded the same file, simply use the existing path
        if ($user->profilePhotos()->where('file_hash', $fileHash)->exists()) {
            $image = $user->profilePhotos()->where('file_hash', $fileHash)->first();

            return [
                'image_id' => $image?->id,
                'file_path' => $image?->path,
                'file_hash' => $image?->file_hash,
            ];
        }

        $path = 'profile-photos/' . $user->email . '/' . $fileHash . '.' . $file->extension();

        if (! Storage::disk('public')->put($path, $file->getContent())) {
            Log::error('Failed to upload profile photo for user: ' . $user->email);
            return false;
        }

        return [
            'image_id' => null,
            'file_path' => $path,
            'file_hash' => $fileHash
        ];
    }
}
