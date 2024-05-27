<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WordFilterResource\Pages;
use App\Models\WordFilter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WordFilterResource extends Resource
{
    protected static ?string $model = WordFilter::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('word')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('replacement')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('bannable')
                    ->required(),
                Forms\Components\Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('word')
                    ->searchable(),
                Tables\Columns\TextColumn::make('replacement')
                    ->searchable(),
                Tables\Columns\IconColumn::make('bannable')
                    ->boolean(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWordFilters::route('/'),
            'create' => Pages\CreateWordFilter::route('/create'),
            'edit' => Pages\EditWordFilter::route('/{record}/edit'),
        ];
    }
}
