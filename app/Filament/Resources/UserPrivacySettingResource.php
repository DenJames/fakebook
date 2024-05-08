<?php

namespace App\Filament\Resources;

use App\Enums\ProfileVisibilityTypes;
use App\Filament\Resources\UserPrivacySettingResource\Pages;
use App\Filament\Resources\UserPrivacySettingResource\RelationManagers;
use App\Models\UserPrivacySetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserPrivacySettingResource extends Resource
{
    protected static ?string $model = UserPrivacySetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('visibility_type')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\Toggle::make('allow_friend_requests')
                    ->required(),
                Forms\Components\Toggle::make('show_biography')
                    ->required(),
                Forms\Components\Toggle::make('show_join_date')
                    ->required(),
                Forms\Components\Toggle::make('show_friend_list')
                    ->required(),
                Forms\Components\Toggle::make('show_photo_list')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('visibility_type')
                    ->formatStateUsing(function ($state) {
                        return $state instanceof ProfileVisibilityTypes ? $state->value : $state;
                    }),
                Tables\Columns\IconColumn::make('allow_friend_requests')
                    ->boolean(),
                Tables\Columns\IconColumn::make('show_biography')
                    ->boolean(),
                Tables\Columns\IconColumn::make('show_join_date')
                    ->boolean(),
                Tables\Columns\IconColumn::make('show_friend_list')
                    ->boolean(),
                Tables\Columns\IconColumn::make('show_photo_list')
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
            'index' => Pages\ListUserPrivacySettings::route('/'),
            'create' => Pages\CreateUserPrivacySetting::route('/create'),
            'edit' => Pages\EditUserPrivacySetting::route('/{record}/edit'),
        ];
    }
}
