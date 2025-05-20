<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GestorResource\Pages;
use App\Filament\Resources\GestorResource\RelationManagers;
use App\Filament\Resources\GestorResource\RelationManagers\IndicadoresRelationManager;
use App\Models\Gestor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GestorResource extends Resource
{
    protected static ?string $model = Gestor::class;

    protected static ?string $navigationGroup = 'Gestores';

    protected static ?string $navigationLabel = 'Gestores';

    protected static ?string $pluralModelLabel = 'Gestores';

    protected static ?string $label = 'Gestor';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->columnSpan(4)
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('unidade')
                    ->columnSpan(3)
                    ->maxLength(255),
                Forms\Components\TextInput::make('setor')
                    ->columnSpan(3)
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unidade')
                    ->searchable(),
                Tables\Columns\TextColumn::make('setor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            IndicadoresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGestors::route('/'),
            'create' => Pages\CreateGestor::route('/create'),
            'edit' => Pages\EditGestor::route('/{record}/edit'),
        ];
    }
}
