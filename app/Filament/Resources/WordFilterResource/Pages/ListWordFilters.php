<?php

namespace App\Filament\Resources\WordFilterResource\Pages;

use App\Filament\Resources\WordFilterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWordFilters extends ListRecords
{
    protected static string $resource = WordFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
