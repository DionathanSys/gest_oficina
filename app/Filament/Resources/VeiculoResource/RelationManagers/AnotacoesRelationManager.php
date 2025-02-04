<?php

namespace App\Filament\Resources\VeiculoResource\RelationManagers;

use App\Enums\{Prioridade, StatusDiversos, TipoAnotacao};
use App\Filament\Resources\ItemManutencaoResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnotacoesRelationManager extends RelationManager
{
    protected static string $relationship = 'anotacoes';

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make(),
            'pendente' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '<>', StatusDiversos::CONCLUIDO)),
            'concluído' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '=', StatusDiversos::CONCLUIDO)),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'pendente';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('data_referencia')
                    ->default(now())
                    ->displayFormat('d/m/Y')
                    ->closeOnDateSelection()
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('tipo_anotacao')
                    ->label('Tipo Anotação')
                    ->options(function () {
                        return collect(TipoAnotacao::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->default(TipoAnotacao::OBSERVACAO)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(function () {
                        return collect(StatusDiversos::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->default(StatusDiversos::PENDENTE)
                    ->required(),
                Forms\Components\Select::make('prioridade')
                    ->required()
                    ->options(function () {
                        return collect(Prioridade::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->default(Prioridade::BAIXA),
                Forms\Components\Select::make('item_manutencao_id')
                    ->label('Item')
                    ->searchable()
                    ->preload()
                    ->relationship('itemManutencao', 'descricao')
                    ->default(null)
                    ->createOptionForm([
                        ItemManutencaoResource::getDescricaoFormField(),
                        ItemManutencaoResource::getComplementoFormField(),
                        ItemManutencaoResource::getAtivoFormField(),
                    ]),
                Forms\Components\Textarea::make('observacao')
                    ->label('Observação')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->modifyQueryUsing(fn($query) => $query->with('veiculo', 'itemManutencao'))
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('itemmanutencao.descricao')
                    ->searchable()
                    ->label('Item'),
                Tables\Columns\TextColumn::make('observacao')
                    ->searchable()
                    ->label('Observação'),
                Tables\Columns\TextColumn::make('data_referencia')
                    ->sortable()
                    ->label('Data Ref.')
                    ->date('d/m/Y'),
                Tables\Columns\SelectColumn::make('tipo_anotacao')
                    ->options(function () {
                        return collect(TipoAnotacao::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->label('Tipo'),
                Tables\Columns\SelectColumn::make('status')
                    ->options(function () {
                        return collect(StatusDiversos::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    }),
                Tables\Columns\SelectColumn::make('prioridade')
                    ->options(function () {
                        return collect(Prioridade::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    }),
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nova Anotação'),
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
                ]),
            ])
            ->defaultSort('data_referencia', 'desc')
            ->groups([
                Group::make('tipo_anotacao')
                    ->label('Tipo Anotação')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),

                Group::make('Prioridade')
                    ->label('Prioridade')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),

                Group::make('status')
                    ->label('Status')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),

                Group::make('data_referencia')
                    ->label('Data Referência')
                    ->collapsible()
                    ->date('d/m/Y'),
            ])
            ->defaultGroup('tipo_anotacao');
    }
}
