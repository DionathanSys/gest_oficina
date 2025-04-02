<?php

namespace App\Filament\Resources\OrdemServicoResource\RelationManagers;

use App\Enums\StatusDiversos;
use App\Filament\Resources\ItemManutencaoResource;
use App\Models\ItemManutencao;
use App\Models\ItemOrdemServico;
use App\Services\OrdemServicoService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class ServicosRelationManager extends RelationManager
{
    protected static string $relationship = 'itens';

    public function form(Form $form): Form
    {
        return $form
            ->columns(8)
            ->schema([
                ItemManutencaoResource::getSelectItemFormFiedl()
                    ->live(true),
                Forms\Components\TextInput::make('posicao')
                    ->reactive()
                    ->label('Posição')
                    ->required(fn(Forms\Get $get) => ItemManutencao::find($get('item_manutencao_id'))->controla_posicao ?? false),
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
                Tables\Columns\Layout\Split::make([
                    Stack::make([
                        Tables\Columns\TextColumn::make('itemManutencao.codigo')
                            ->label('Item')
                            ->visibleFrom('lg'),
                        Tables\Columns\TextColumn::make('itemManutencao.descricao')
                            ->label('Item'),
                    ]),
                ])->from('sm'),
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('observacao')
                        ->placeholder('Observações: N/A')
                        ->label('Observação')
                            ->visibleFrom('sm'),
                    Tables\Columns\TextColumn::make('posicao')
                        ->prefix('Pos. ')
                        ->placeholder('Posição: N/A')
                        ->label('Posição')
                            ->visibleFrom('sm'),
                    Tables\Columns\SelectColumn::make('status')
                        ->options(StatusDiversos::toSelectArray())
                            ->visibleFrom('sm'),
                    Stack::make([
                        Tables\Columns\TextColumn::make('observacao')
                            ->placeholder('Observações: N/A')
                            ->label('Observação'),
                        Tables\Columns\TextColumn::make('posicao')
                            ->prefix('Pos. ')
                            ->placeholder('Posição: N/A')
                            ->label('Posição'),
                        Tables\Columns\SelectColumn::make('status')
                            ->options(StatusDiversos::toSelectArray()),
                    ])->hiddenFrom('sm')->space(3),
                ])->from('sm')->collapsible(false)
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Novo')
                    ->icon('heroicon-o-plus')
                    ->using(fn(array $data): ItemOrdemServico => OrdemServicoService::addItem($this->ownerRecord, $data))
                    ->modalHeading('Novo Item de Mant.'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('encerrar')
                        ->action(fn(Collection $itens) => OrdemServicoService::encerrarServico($itens))

                ]),
            ])
            ->poll('3s')
            ->emptyStateDescription('');
    }
}
