<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ViagemAgroResource\Pages;
use App\Filament\Resources\ViagemAgroResource\RelationManagers;
use App\Models\ViagemAgro;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ViagemAgroResource extends Resource
{
    protected static ?string $model = ViagemAgro::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('referencia')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nro_viagem')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nro_nota')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('data')
                    ->required(),
                Forms\Components\TextInput::make('placa')
                    ->required()
                    ->maxLength(7),
                Forms\Components\TextInput::make('km')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('frete')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('destino')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('local')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('vlr_cte')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('vlr_nfs')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('referencia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_viagem')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nro_nota')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('placa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('km')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('frete')
                    ->money('BRL', 100)
                    ->sortable(),
                Tables\Columns\TextColumn::make('destino')
                    ->searchable(),
                Tables\Columns\TextColumn::make('local')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vlr_cte')
                    ->money('BRL', 100)
                    ->sortable(),
                Tables\Columns\TextColumn::make('vlr_nfs')
                    ->money('BRL', 100)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Group::make('placa')->collapsible(),
                Group::make('destino')->collapsible(),
                ])
            ->filters([
                SelectFilter::make('placa')
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
            'index' => Pages\ListViagemAgros::route('/'),
            'create' => Pages\CreateViagemAgro::route('/create'),
            'edit' => Pages\EditViagemAgro::route('/{record}/edit'),
        ];
    }
}
