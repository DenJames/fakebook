<?php

namespace App\Filament\Resources\UserProfilePhotoResource\Pages;

use App\Filament\Resources\UserProfilePhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserProfilePhoto extends CreateRecord
{
    protected static string $resource = UserProfilePhotoResource::class;
}
