<?php

namespace App\Filament\Resources\Parceiro;

use App\Filament\Resources\Parceiro\ParceiroResource\Pages;
use App\Filament\Resources\Parceiro\ParceiroResource\RelationManagers;
use App\Models\Parceiro\Parceiro;
use Filament\Forms;
use Filament\Forms\Components\{
    TextInput, 
    Select,
    Toggle,
};
use Filament\Tables\Columns\{
    TextColumn,
    ToggleColumn,
};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParceiroResource extends Resource
{
    protected static ?string $model = Parceiro::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nome')
                    ->required()
                    ->autocomplete(false)
                    ->minLength(5)
                    ->maxLength(255),
                TextInput::make('cadastro_unico')
                    ->hint('Apenas números')
                    ->numeric()
                    ->autocomplete(false)
                    ->required()
                    ->maxLength(255),
                Select::make('tipo')
                    ->label('Tipo de Pessoa')
                    ->options([
                        'fisica' => 'Pessoa Fisica',
                        'juridica' => 'Pessoa Jurídica'
                    ])
                    ->default('juridica')
                    ->required(),
                Select::make('vinculo')
                    ->options([
                        'cliente' => 'Cliente',
                        'fornecedor' => 'fornecedor',
                        'colaborador' => 'colaboradore',
                    ])
                    ->default('cliente')
                    ->required(),
                Toggle::make('status')
                    ->required()
                    ->default(true)
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->searchable(),
                TextColumn::make('cadastro_unico')
                    ->label('CPF/CNPJ')
                    ->searchable(),
                TextColumn::make('tipo')
                    ->prefix('Pessoa ')
                    ->label('Tipo de Pessoa')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('vinculo')
                    ->label('Tipo de Vínculo')
                    ->formatStateUsing(fn (string $state): string => ucwords($state))
                    ->toggleable(isToggledHiddenByDefault: false),
                ToggleColumn::make('status')
                    ->beforeStateUpdated(function ($record, $state) {
                        $record->update(['status' => !$state]);}),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
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
            'index' => Pages\ListParceiros::route('/'),
            'create' => Pages\CreateParceiro::route('/create'),
            'edit' => Pages\EditParceiro::route('/{record}/edit'),
        ];
    }
}
