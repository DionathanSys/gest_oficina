<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemManutencaoResource\Pages;
use App\Filament\Resources\ItemManutencaoResource\RelationManagers;
use App\Models\ItemManutencao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemManutencaoResource extends Resource
{
    protected static ?string $model = ItemManutencao::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationGroup = 'Mant.';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Itens Mant.';

    protected static ?string $label = 'Itens Mant.';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::getDescricaoFormField(),
                static::getComplementoFormField(),
                static::getAtivoFormField(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->searchable(),
                Tables\Columns\TextColumn::make('complemento')
                    ->searchable(),
                Tables\Columns\IconColumn::make('ativo')
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
            'index' => Pages\ListItemManutencaos::route('/'),
            // 'create' => Pages\CreateItemManutencao::route('/create'),
            // 'edit' => Pages\EditItemManutencao::route('/{record}/edit'),
        ];
    }

    public static function getDescricaoFormField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('descricao')
            ->label('Descrição')
                ->autocomplete(false)
            ->required()
            ->maxLength(200);
    }
    public static function getComplementoFormField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('complemento')
            ->autocomplete(false)
            ->maxLength(200);
    }
    public static function getAtivoFormField(): Forms\Components\Toggle
    {
        return Forms\Components\Toggle::make('ativo')
            ->default(true)  
            ->required();
    }
}
