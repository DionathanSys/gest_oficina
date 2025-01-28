<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComentarioAnotacaoResource\Pages;
use App\Filament\Resources\ComentarioAnotacaoResource\RelationManagers;
use App\Models\ComentarioAnotacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComentarioAnotacaoResource extends Resource
{
    protected static ?string $model = ComentarioAnotacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::getComentarioFormField(),
                
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
            'index' => Pages\ListComentarioAnotacaos::route('/'),
            'create' => Pages\CreateComentarioAnotacao::route('/create'),
            'edit' => Pages\EditComentarioAnotacao::route('/{record}/edit'),
        ];
    }

    public static function getComentarioFormField(): Forms\Components\Textarea
    {
        return Forms\Components\Textarea::make('comentario')
            ->label('Comentário')
            ->required()
            ->placeholder('Digite o comentário');
    }

}
