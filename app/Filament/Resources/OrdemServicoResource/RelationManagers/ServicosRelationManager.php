<?php

namespace App\Filament\Resources\OrdemServicoResource\RelationManagers;

use App\Enums\StatusDiversos;
use App\Models\ItemManutencao;
use App\Services\OrdemServicoService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServicosRelationManager extends RelationManager
{
    protected static string $relationship = 'itens';

    public function form(Form $form): Form
    {
        return $form
            ->columns(7)
            ->schema([
                Forms\Components\Select::make('item_manutencao_id')
                    ->label('Item Manutenção')
                    ->relationship('itemManutencao', 'descricao')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan(5),
                Forms\Components\TextInput::make('posicao')
                    ->label('Posição')
                    ->required(fn(Forms\Get $get) => ItemManutencao::find($get('item_manutencao_id'))->controle_posicao ?? false),
                Forms\Components\Textarea::make('observacao')
                    ->label('Observação')
                    ->autocomplete(false)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_manutencao_id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('itemManutencao.descricao')
                    ->label('Item'),
                Tables\Columns\TextColumn::make('observacao')
                    ->label('Observação'),
                Tables\Columns\SelectColumn::make('status')
                    ->options(StatusDiversos::toSelectArray())
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Novo')
                    ->icon('heroicon-o-plus')
                    ->action(fn(array $data) => OrdemServicoService::addItem($this->ownerRecord, $data))
                    ->modalHeading('Novo Item de Mant.'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateDescription('');
    }
}
















