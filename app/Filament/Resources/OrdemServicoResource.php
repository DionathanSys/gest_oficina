<?php

namespace App\Filament\Resources;

use App\Enums\StatusDiversos;
use App\Enums\StatusOrdemSankhya;
use App\Filament\Resources\OrdemServicoResource\Pages;
use App\Filament\Resources\OrdemServicoResource\RelationManagers;
use App\Filament\Resources\OrdemServicoResource\RelationManagers\ServicosRelationManager;
use App\Models\OrdemServico;
use App\Services\OrdemServicoService;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdemServicoResource extends Resource
{
    protected static ?string $model = OrdemServico::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Mant.';

    protected static ?string $pluralModelLabel = 'Ordens de Serviço';

    protected static ?string $pluralLabel = 'Ordens de Serviço';

    protected static ?string $label = 'Ordem de Serviço';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([
                    'default'   => 1,
                    'sm'        => 1,
                    'lg'        => 5,
                ])
                    ->schema([
                        Forms\Components\TextInput::make('nro_ordem')
                            ->label('Nro.OS'),
                        Forms\Components\Select::make('veiculo_id')
                            ->placeholder('Placa')
                            ->relationship('veiculo', 'placa')
                            ->searchable()
                            ->preload()
                            ->label('Veículo'),
                        Forms\Components\TextInput::make('km')
                            ->numeric(),
                        Forms\Components\Select::make('tipo_manutencao')
                            ->options([
                                'CORRETIVA'     => 'CORRETIVA',
                                'PREVENTIVA'    => 'PREVENTIVA',
                                'PREDITIVA'     => 'PREDITIVA',
                                'SOCORRO'       => 'SOCORRO',
                            ])
                            ->default('CORRETIVA')
                            ->label('Tipo de Manutenção'),
                        Forms\Components\Select::make('status')
                            ->options(fn() => StatusDiversos::toSelectArray())
                            ->default(StatusDiversos::PENDENTE),
                        Forms\Components\Select::make('status_sankhya')
                            ->label('Sankhya')
                            ->options(fn() => StatusOrdemSankhya::toSelectArray())
                            ->default(StatusDiversos::PENDENTE),
                        Forms\Components\DatePicker::make('data_abertura')
                            ->label('Data de Abertura')
                            ->default(now()),
                        Forms\Components\DatePicker::make('data_encerramento')
                            ->label('Data de Encerramento'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextInputColumn::make('nro_ordem')
                    ->afterStateUpdated(function(OrdemServico $record){
                        OrdemServicoService::setNroOrdem($record);
                    })
                    ->label('Nro.OS')
                    ->searchable()
                    ->sortable()
                    ->width('4%'),
                Tables\Columns\TextColumn::make('veiculo.placa')    
                    ->label('Veículo'),
                Tables\Columns\TextColumn::make('km'),
                Tables\Columns\TextColumn::make('tipo_manutencao')
                    ->label('Tipo Mant.'),
                Tables\Columns\TextColumn::make('data_abertura')
                    ->label('Dt. Abertura')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('data_encerramento')
                    ->label('Dt. Encerramento')
                    ->date('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\SelectColumn::make('status')
                    ->options(StatusDiversos::toSelectArray()),
                Tables\Columns\SelectColumn::make('status_sankhya')
                    ->options(StatusOrdemSankhya::toSelectArray())
                    ->label('Sankhya'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Inserido Em')
                    ->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Editado Em')
                    ->toggleable(isToggledHiddenByDefault:true),

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
            ->defaultSort('nro_ordem')
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('add_item')
                        ->label('Adicionar Item')
                        ->icon('heroicon-o-plus-circle'),
                    Tables\Actions\Action::make('executar')
                        ->label('Em Execução')
                        ->icon('heroicon-o-play'),
                    Tables\Actions\Action::make('finalizar')
                        ->label('Finalizar')
                        ->icon('heroicon-o-check-circle'),
                    Tables\Actions\Action::make('finalizar_sankhya')
                        ->label('Finalizar Sankhya')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn(OrdemServico $record) => OrdemServicoService::encerrarOrdemSankhya($record)),
                ]),
                    
            ], ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->poll('3s')
            ->emptyStateDescription('');
    }

    public static function getRelations(): array
    {
        return [
            ServicosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrdemServicos::route('/'),
            // 'create' => Pages\CreateOrdemServico::route('/create'),
            'edit' => Pages\EditOrdemServico::route('/{record}/edit'),
        ];
    }
}
