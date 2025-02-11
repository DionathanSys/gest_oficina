<?php

namespace App\Filament\Resources;

use App\Enums\Prioridade;
use App\Enums\StatusDiversos;
use App\Enums\TipoAnotacao;
use App\Filament\Resources\AnotacaoVeiculoResource\Pages;
use App\Filament\Resources\AnotacaoVeiculoResource\RelationManagers;
use App\Filament\Resources\AnotacaoVeiculoResource\RelationManagers\ComentariosRelationManager;
use App\Models\AnotacaoVeiculo;
use App\Models\ComentarioAnotacao;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;

class AnotacaoVeiculoResource extends Resource
{
    protected static ?string $model = AnotacaoVeiculo::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Mant.';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Acompanhamento Mant.';

    protected static ?string $label = 'Acompanhamento Mant.';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\Select::make('veiculo_id')
                    ->default(fn() => session('veiculo_id') ?? null)
                    ->columnSpan(6)
                    ->relationship('veiculo', 'placa')
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('data_referencia')
                    ->default(fn() => session('data_referencia') ?? now())
                    ->columnSpan(6)
                    ->closeOnDateSelection()
                    ->native(false)
                    ->required(),

                Forms\Components\Select::make('tipo_anotacao')
                    ->default(fn() => session('tipo_anotacao') ?? TipoAnotacao::MANUTENCAO) 
                    ->columnSpan(6)
                    ->label('Tipo Anotação')
                    ->options(function () {
                        return collect(TipoAnotacao::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->required(),
                Forms\Components\Select::make('status')
                    ->columnSpan(6)
                    ->options(function () {
                        return collect(StatusDiversos::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->default(fn() => session('status.anotacao') ?? StatusDiversos::PENDENTE)
                    ->required(),
                Forms\Components\Select::make('prioridade')
                    ->columnSpan(6)
                    ->required()
                    ->options(function () {
                        return collect(Prioridade::cases())
                            ->mapWithKeys(fn($prioridade) => [$prioridade->value => $prioridade->value])
                            ->toArray();
                    })
                    ->default(fn()=> session('prioridade') ?? Prioridade::BAIXA),

                Forms\Components\Select::make('item_manutencao_id')
                    ->columnSpan(6)
                    ->relationship('itemManutencao', 'descricao')
                    ->preload()
                    ->searchable()
                    ->default(null)
                    ->createOptionForm([
                        ItemManutencaoResource::getDescricaoFormField(),
                        ItemManutencaoResource::getComplementoFormField(),
                        ItemManutencaoResource::getAtivoFormField(),
                    ]),
                Forms\Components\TextInput::make('km')
                    ->columnSpan(2)
                    ->numeric(255)
                    ->default(fn() => session('km') ?? null),

                Forms\Components\Textarea::make('observacao')
                    ->columnSpanFull()
                    ->label('Observação')
                    ->maxLength(255)
                    ->default(null),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query->with('veiculo', 'itemManutencao');
            })
            ->columns([
                Tables\Columns\TextColumn::make('veiculo.placa')
                    ->label('Placa')
                    ->numeric()
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('itemManutencao.descricao')
                    ->searchable(isIndividual: true)
                    ->label('Item')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('observacao')
                    ->label('Observação')
                    ->searchable(),
                Tables\Columns\TextColumn::make('km')
                    ->label('Km'),
                
                Tables\Columns\TextColumn::make('comentarios')
                    ->badge()
                    ->label('Comentários')
                    ->url(fn(AnotacaoVeiculo $record) => AnotacaoVeiculoResource::getUrl('edit', ['record' => $record->id]))
                    ->openUrlInNewTab()
                    ->formatStateUsing(function(AnotacaoVeiculo $record) {
                        return $record->comentarios->count();
                    }),
                Tables\Columns\TextColumn::make('data_referencia')
                    ->label('Data Ref.')
                    ->date('d/m/Y')
                    ->sortable(),
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
                SelectFilter::make('tipo_anotacao')
                    ->label('Tipo Anotação')
                    ->multiple()
                    ->options(function () {
                        return collect(TipoAnotacao::cases())
                            ->mapWithKeys(fn($tipo_anotacao) => [$tipo_anotacao->value => $tipo_anotacao->value])
                            ->toArray();
                    }),
                SelectFilter::make('veiculo.placa')
                    ->label('Placa')
                    ->multiple()
                    ->preload()
                    ->relationship('veiculo', 'placa'),
                Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->label('Status não é')
                            ->multiple()
                            ->options(function () {
                                return collect(StatusDiversos::cases())
                                    ->mapWithKeys(fn($status) => [$status->value => $status->value])
                                    ->toArray();
                            })
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['status'],
                                fn(Builder $query, $status): Builder => $query->where('status', '!=', $status)
                            );
                    }),
                Filter::make('data_referencia')
                    ->form([
                        DatePicker::make('data_inicio'),
                        DatePicker::make('data_fim'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['data_inicio'],
                                fn(Builder $query, $date): Builder => $query->whereDate('data_referencia', '>=', $date)
                            )
                            ->when(
                                $data['data_fim'],
                                fn(Builder $query, $date): Builder => $query->whereDate('data_referencia', '<=', $date)
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\Action::make('comentario')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->iconButton()
                    ->form([
                        ComentarioAnotacaoResource::getComentarioFormField(),
                    ])
                    ->action(function (array $data, AnotacaoVeiculo $record) {
                        ComentarioAnotacao::create([
                            'anotacao_veiculo_id' => $record->id,
                            'user_id' => Auth::id(),
                            'comentario' => $data['comentario'],
                        ]);
                    })
            ])
            ->recordUrl(
                fn (AnotacaoVeiculo $record) => VeiculoResource::getUrl('edit', ['record' => $record->veiculo->id])
                )
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
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

                Group::make('veiculo.placa')
                    ->label('Placa')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),

                Group::make('data_referencia')
                    ->label('Data Referência')
                    ->collapsible()
                    ->date('d/m/Y'),
            ])
            ->paginated(['25', '50'])
            ->defaultGroup('veiculo.placa');
    }

    public static function getRelations(): array
    {
        return [
            ComentariosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnotacaoVeiculos::route('/'),
            // 'create' => Pages\CreateAnotacaoVeiculo::route('/create'),
            'edit' => Pages\EditAnotacaoVeiculo::route('/{record}/edit'),
        ];
    }
}
