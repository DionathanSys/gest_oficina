<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComentarioAnotacaoResource\Pages;
use App\Filament\Resources\ComentarioAnotacaoResource\RelationManagers;
use App\Models\ComentarioAnotacao;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComentarioAnotacaoResource extends Resource
{
    protected static ?string $model = ComentarioAnotacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Mant.';

    protected static ?string $pluralModelLabel = 'Comentários de Anotações';

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
                Tables\Columns\TextColumn::make('itemManutencao.descricao')
                    ->searchable()
                    ->label('Descrição'),
                Tables\Columns\TextColumn::make('itemManutencao.observacao')
                    ->searchable()
                    ->label('Observação'),
                Tables\Columns\TextColumn::make('comentario')
                    ->searchable()
                    ->label('Comentário')
                    ->wrap(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('Usuário'),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Inserido em'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Atualizado em'),
            ])
            ->groups([
                Tables\Grouping\Group::make('itemManutencao.descricao')->collapsible(),
                Tables\Grouping\Group::make('veiculo.placa')->collapsible()
            ])
            ->defaultGroup('veiculo.placa')
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
