<?php

namespace App\Filament\Clusters\Clientes\Resources;

use App\Filament\Clusters\Clientes;
use App\Filament\Clusters\Clientes\Resources\FaturaResource\Pages;
use App\Filament\Clusters\Clientes\Resources\FaturaResource\RelationManagers;
use App\Models\Fatura;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaturaResource extends Resource
{
    protected static ?string $model = Fatura::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Clientes::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListFaturas::route('/'),
            'create' => Pages\CreateFatura::route('/create'),
            'edit' => Pages\EditFatura::route('/{record}/edit'),
        ];
    }
}
