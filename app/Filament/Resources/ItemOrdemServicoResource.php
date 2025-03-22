<?php

namespace App\Filament\Resources;

use App\Enums\StatusOrdemSankhya;
use App\Filament\Resources\ItemOrdemServicoResource\Pages;
use App\Filament\Resources\ItemOrdemServicoResource\RelationManagers;
use App\Models\ItemOrdemServico;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemOrdemServicoResource extends Resource
{
    protected static ?string $model = ItemOrdemServico::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Mant.';

    protected static ?string $pluralModelLabel = 'Itens OS';

    protected static ?string $pluralLabel = 'Itens OS';

    protected static ?string $label = 'Itens OS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ordem_servico_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('item_manutencao_id')
                    ->relationship('itemManutencao', 'id')
                    ->required(),
                Forms\Components\TextInput::make('observacao')
                    ->maxLength(255),
                Forms\Components\TextInput::make('posicao')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ordemServico.nro_ordem')
                    ->label('Nro Ordem')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\TextColumn::make('veiculo.placa')
                    ->label('Placa')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('itemManutencao.descricao')
                    ->label('Item')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('observacao')
                    ->label('Observação'),
                Tables\Columns\TextColumn::make('posicao')
                    ->label('Posição'),
                Tables\Columns\TextColumn::make('status'),
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
                Tables\Filters\SelectFilter::make('veiculo_id')
                    ->label('Placa')
                    ->multiple()
                    ->preload()
                    ->relationship('veiculo', 'placa'),
                Tables\Filters\SelectFilter::make('status')
                    ->options(StatusOrdemSankhya::toSelectArray()),
                Tables\Filters\Filter::make('data_abertura')
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                
                        if ($data['data_inicio'] ?? null) {
                            $indicators[] = Indicator::make('Dt. Inicio ' . Carbon::parse($data['data_inicio'])->toFormattedDateString())
                                ->removeField('data_inicio');
                        }
                
                        if ($data['data_fim'] ?? null) {
                            $indicators[] = Indicator::make('Dt. Fim ' . Carbon::parse($data['data_fim'])->toFormattedDateString())
                                ->removeField('data_fim');
                        }
                
                        return $indicators;
                    })
                    ->form([
                        DatePicker::make('data_inicio')
                            ->label('Dt. Abertura Inicio'),
                        DatePicker::make('data_fim')
                            ->label('Dt. Abertura Fim'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['data_inicio'],
                                fn(Builder $query, $date): Builder => $query->whereDate('data_abertura', '>=', $date)
                            )
                            ->when(
                                $data['data_fim'],
                                fn(Builder $query, $date): Builder => $query->whereDate('data_abertura', '<=', $date)
                            );
                    }),
                Tables\Filters\Filter::make('data_encerramento')
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                
                        if ($data['data_inicio'] ?? null) {
                            $indicators[] = Indicator::make('Dt. Inicio ' . Carbon::parse($data['data_inicio'])->toFormattedDateString())
                                ->removeField('data_inicio');
                        }
                
                        if ($data['data_fim'] ?? null) {
                            $indicators[] = Indicator::make('Dt. Fim ' . Carbon::parse($data['data_fim'])->toFormattedDateString())
                                ->removeField('data_fim');
                        }
                
                        return $indicators;
                    })
                    ->form([
                        DatePicker::make('data_inicio')
                            ->label('Dt. Encerramento Inicio'),
                        DatePicker::make('data_fim')
                            ->label('Dt. Encerramento Fim'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['data_inicio'],
                                fn(Builder $query, $date): Builder => $query->whereDate('data_encerramento', '>=', $date)
                            )
                            ->when(
                                $data['data_fim'],
                                fn(Builder $query, $date): Builder => $query->whereDate('data_encerramento', '<=', $date)
                            );
                    })
            ])
            ->persistFiltersInSession()
            ->defaultSort('ordemServico.nro_ordem')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('veiculo.placa')
                    ->label('Placa'),
                Tables\Grouping\Group::make('ordemServico.nro_ordem')
                    ->label('Nro. Ordem'),
                Tables\Grouping\Group::make('itemManutencao.descricao')
                    ->label('Item'),
            ])
            ->defaultGroup('ordemServico.nro_ordem');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageItemOrdemServicos::route('/'),
        ];
    }
}
