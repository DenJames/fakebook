<?php

namespace App\Filament\Resources\UserProfilePhotoResource\Pages;

use App\Filament\Resources\UserProfilePhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserProfilePhoto extends EditRecord
{
    protected static string $resource = UserProfilePhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
