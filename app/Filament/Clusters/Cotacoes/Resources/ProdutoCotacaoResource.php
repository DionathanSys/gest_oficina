<?php

namespace App\Filament\Clusters\Cotacoes\Resources;

use App\Actions\AprovarProdutoAction;
use App\Enums\StatusCotacaoEnum;
use App\Filament\Clusters\Cotacoes;
use App\Filament\Clusters\Cotacoes\Resources\ProdutoCotacaoResource\{Pages, RelationManagers};
use App\Models\{Cotacao, Produto, ProdutoCotacao, PropostaCotacao};
use App\Models\Parceiro\Fornecedor;
use Filament\Forms;
use Filament\Forms\Components\{TextInput, DatePicker, Toggle, Select};
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\{Action, ActionGroup, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdutoCotacaoResource extends Resource
{
    protected static ?string $model = ProdutoCotacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Cotações';
    
    protected static ?string $navigationLabel = 'Itens';

    protected static ?string $label = 'Itens Cotações';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $cluster = Cotacoes::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cotacao_id')
                    ->label('Cotações')
                    ->relationship('cotacao', 'descricao')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('descricao')
                            ->label('Descrição')
                            ->maxLength(150),

                        TextInput::make('setor')
                            ->label('Setor Vínculo')
                            ->default('Frota Agro')
                            ->required()
                            ->maxLength(100),

                        Select::make('prioridade')
                            ->default('Media')
                            ->options([
                                'Baixa' => 'Baixa',
                                'Media' => 'Média',
                                'Alta' => 'Alta',
                            ])
                            ->required(),

                        DatePicker::make('data')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now())
                            ->required(),

                        Select::make('status')
                            ->options([
                                collect(StatusCotacaoEnum::cases())
                                    ->mapWithKeys(fn($cases) => [$cases->value => $cases->getOptions()])
                                    ->toArray()

                            ])
                            ->default(StatusCotacaoEnum::PENDENTE),
                            
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $cotacao = Cotacao::create($data);
                        return $cotacao->id;
                    }),

                Select::make('produto_id')
                    ->label('Item')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->relationship('produto', 'descricao')
                    ->createOptionForm([
                        TextInput::make('sankhya_id')
                            ->label('Cód. Sankhya')
                            ->numeric(),

                        TextInput::make('descricao')
                            ->label('Descrição')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('complemento')
                            ->maxLength(180),

                        Toggle::make('ativo')
                            ->default(1)
                            ->disabled()
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $produto = Produto::create($data);
                        return $produto->id;
                    }),

                TextInput::make('quantidade')
                    ->numeric()
                    ->required(),

                Select::make('status')

                    ->options([
                        collect(StatusCotacaoEnum::cases())
                            ->mapWithKeys(fn($cases) => [$cases->value => $cases->getOptions()])
                            ->toArray()

                    ])
                    ->default('Pendente')
                    ->required(),

                TextInput::make('observacao')
                    ->label('Observações')
                    ->maxLength(180)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cotacao.descricao')
                    ->label('Cotação'),

                TextColumn::make('produto.descricao')
                    ->label('Item'),

                TextColumn::make('quantidade'),

                TextColumn::make('status')
                    ->badge(fn ($state): string => match ($state) {
                        \App\Enums\StatusCotacaoEnum::PENDENTE => 'primary',
                        \App\Enums\StatusCotacaoEnum::EM_ANDAMENTO => 'info',
                        \App\Enums\StatusCotacaoEnum::REPROVADO => 'danger',
                        \App\Enums\StatusCotacaoEnum::APROVADO => 'info',
                        \App\Enums\StatusCotacaoEnum::FECHADO => 'success',
                        default => 'secondary',
                    })
                    ->badge(),

                TextColumn::make('observacao')
                    ->label('Observação')
                    ->toggledHiddenByDefault(),

            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        collect(StatusCotacaoEnum::cases())
                            ->mapWithKeys(fn($cases) => [$cases->value => $cases->getOptions()])
                            ->toArray()
                    ])
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('nova-proposta')
                        ->label('Nova Proposta')
                        ->icon('heroicon-o-plus-circle')
                        ->form([
                            Select::make('fornecedor')
                                ->options(Fornecedor::query()->pluck('nome', 'id'))
                                ->preload()
                                ->searchable()
                                ->required(),
                            TextInput::make('produto_id')
                                ->readOnly()
                                ->default(fn(ProdutoCotacao $record) => $record->produto_id),

                            TextInput::make('produto')
                                ->readOnly()
                                ->default(fn(ProdutoCotacao $record) => $record->produto->descricao),

                            TextInput::make('valor')
                                ->numeric()
                                ->prefix('R$'),
                        ])
                        ->action(
                            function (array $data, ProdutoCotacao $record) {
                                PropostaCotacao::create([
                                    'cotacao_id' => $record->cotacao_id,
                                    'produto_id' => $data['produto_id'],
                                    'fornecedor_id' => $data['fornecedor'],
                                    'valor' => $data['valor'],
                                    'status' => StatusCotacaoEnum::PENDENTE,
                                ]);
                                $record->update(
                                    [
                                        'status' => StatusCotacaoEnum::EM_ANDAMENTO
                                    ]
                                );
                                $record->cotacao->update(
                                    [
                                        'status' => StatusCotacaoEnum::EM_ANDAMENTO
                                    ]
                                );
                            }

                        ),
                    Action::make('Aprovar Propostas')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->action(function (Action $action, ProdutoCotacao $record) {
                            AprovarProdutoAction::exec($record);
                            $record->update(
                                [
                                    'status' => StatusCotacaoEnum::APROVADO
                                ]
                            );
                        })
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProdutoCotacaos::route('/'),
        ];
    }
}
