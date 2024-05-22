<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Loop all posts and move the images to the new location
        foreach (User::all() as $user) {
            Log::info('Processing user: ' . $user->id);
            foreach ($user->posts as $post) {
                Log::info('Processing post: ' . $post->id);
                foreach ($post->images as $image) {
                    Log::info('Processing image: ' . $image->id);
                    $newPath = str_replace($post->user->email, $post->user->id, $image->path);
                    $image->update(['path' => $newPath]);
                }
            }

            foreach ($user->profilePhotos as $image) {
                Log::info('Processing profile photo: ' . $image->id);
                $newPath = str_replace($user->email, $user->id, $image->path);
                $image->update(['path' => $newPath]);
            }

            if (Storage::disk('public')->exists('posts/' . $user->email)) {
                Log::info('Moving posts for user: ' . $user->id . ' from ' . $user->email . ' to ' . $user->id);
                Storage::disk('public')->move('posts/' . $user->email, 'posts/' . $user->id);
            }

            if (Storage::disk('public')->exists('cover-photos/' . $user->email)) {
                Log::info('Moving cover photo for user: ' . $user->id . ' from ' . $user->email . ' to ' . $user->id);
                Storage::disk('public')->move('cover-photos/' . $user->email, 'cover-photos/' . $user->id);
            }

            if (Storage::disk('public')->exists('profile-photos/' . $user->email)) {
                Log::info('Moving profile photo for user: ' . $user->id . ' from ' . $user->email . ' to ' . $user->id);
                Storage::disk('public')->move('profile-photos/' . $user->email, 'profile-photos/' . $user->id);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
