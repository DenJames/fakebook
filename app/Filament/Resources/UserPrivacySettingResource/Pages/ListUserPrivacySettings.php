<?php

namespace App\Filament\Resources\UserPrivacySettingResource\Pages;

use App\Filament\Resources\UserPrivacySettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserPrivacySettings extends ListRecords
{
    protected static string $resource = UserPrivacySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
