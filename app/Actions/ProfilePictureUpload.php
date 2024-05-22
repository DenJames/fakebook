<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfilePictureUpload
{
    public function execute(User $user, UploadedFile $file, string $storagePath = 'profile-photos'): bool|array
    {
        $fileHash = md5($file->getContent() . $storagePath . Auth::id());

        // If the user has previously uploaded the same file, simply use the existing path
        $image = $user->profilePhotos()->where('file_hash', $fileHash)->first();
        if ($image) {
            return [
                'image_id' => $image->id,
                'file_path' => $image->path,
                'file_hash' => $image->file_hash,
            ];
        }

        $path = $storagePath . '/' . $user->id . '/' . $fileHash . '.' . $file->extension();

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
