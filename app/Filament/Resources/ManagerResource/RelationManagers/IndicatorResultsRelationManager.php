<?php

namespace App\Filament\Resources\ManagerResource\RelationManagers;

use App\Actions\Indicators\CreateRegistroResultadoIndicadorAction;
use App\Filament\Resources\IndicatorResultResource;
use App\Models\Indicator;
use App\Models\IndicatorResult;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndicatorResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'indicatorResults';

    protected static ?string $title = 'Resultados';

    protected static bool $isLazy = false;

    public function form(Form $form): Form
    {
        return $form
            ->columns(8)
            ->schema([
                Forms\Components\Select::make('indicator_id')
                    ->options(Indicator::query()
                        ->whereHas('managers', function (Builder $query) {
                            $query->where('manager_id', $this->ownerRecord->id);
                        })
                        ->pluck('descricao', 'id'))
                    ->columnSpanFull()
                    ->searchable()
                    ->preload()
                    ->label('Indicador')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $indicator = Indicator::find($state);
                            $set('pontuacao_obtida', 0);
                            $set('peso', $indicator->peso ?? 0);
                            return;
                        }
                        $set('pontuacao_obtida', 0);
                    }),
                Forms\Components\DatePicker::make('periodo')
                    ->columnStart(1)
                    ->columnSpan(2)
                    ->label('Período')
                    ->native(false)
                    ->closeOnDateSelection()
                    ->displayFormat('d/m/Y')
                    ->default(Carbon::now()->subMonth()->startOfMonth())
                    ->required(),
                Forms\Components\Select::make('resultado')
                    ->columnSpan(2)
                    ->options([
                        'ATENDIDO'              => 'Atendido',
                        'ATENDIDO_PARCIALMENTE' => 'Atendido Parcialmente',
                        'NAO_ATENDIDO'          => 'Não Atendido',
                    ])
                    ->default('NAO_ATENDIDO'),
                Forms\Components\TextInput::make('pontuacao_obtida')
                    ->columnSpan(2)
                    ->label('Pontuação Obtida')
                    ->reactive()
                    ->numeric(),
                Forms\Components\TextInput::make('peso')
                    ->columnSpan(2)
                    ->readOnly()
                    ->label('Peso')
                    ->reactive()
                    ->numeric(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('indicator.descricao')
                    ->label('Indicador'),
                Tables\Columns\TextColumn::make('periodo')
                    ->label('Período')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('resultado')
                    ->label('Resultado'),
                Tables\Columns\TextColumn::make('peso')
                    ->label('Peso'),
                Tables\Columns\TextColumn::make('pontuacao_obtida')
                    ->label('Pontuação Obtida'),
            ])
            ->filters([
                //
            ])
            ->groups([
                Tables\Grouping\Group::make('indicator.descricao')
                    ->label('Indicador'),
                Tables\Grouping\Group::make('periodo')
                    ->label('Período')
                    ->getTitleFromRecordUsing(fn(IndicatorResult $record) => \Carbon\Carbon::parse($record->periodo)->format('m/Y')),
            ])
            ->groupingSettingsInDropdownOnDesktop()
            ->defaultSort('periodo', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Resultado')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Novo Resultado')
                    ->using(function (array $data, string $model): Model {
                        $data['manager_id'] = $this->ownerRecord->id;
                        if ($data['resultado'] == 'NAO_ATENDIDO') {
                            $data['pontuacao_obtida'] = 0;
                        }
                        return $model::create($data);
                    })
                    ->after(function (IndicatorResult $record) {
                        if ($record->indicator->tipo == 'COLETIVO') {
                            CreateRegistroResultadoIndicadorAction::exec($record);
                        }
                    }),
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
            ->emptyStateHeading('Sem registros')
            ->emptyStateDescription('Clique em "+ Resultado" para adicionar um novo registro.');
    }
}
