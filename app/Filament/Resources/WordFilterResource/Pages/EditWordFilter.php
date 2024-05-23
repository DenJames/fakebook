<?php

namespace App\Filament\Resources\WordFilterResource\Pages;

use App\Filament\Resources\WordFilterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWordFilter extends EditRecord
{
    protected static string $resource = WordFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
