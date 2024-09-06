<?php

namespace App\Filament\Resources\AcertoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ViagensRelationManager extends RelationManager
{
    protected static string $relationship = 'viagens';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('motorista_id')
            ->columns([
                Tables\Columns\TextColumn::make('viagem.placa')
                    ->label('Placa'),

                Tables\Columns\TextColumn::make('nro_nota')
                    ->sortable(),

                Tables\Columns\TextColumn::make('motorista')
                    ->label('Motorista')
                    ->placeholder('Sem dupla')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('dupla')
                    ->label('Dupla')
                    ->placeholder('Sem dupla')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('frete')
                    ->money('BRL')
                    ->summarize(Sum::make()->money('BRL', 100)),

                Tables\Columns\TextColumn::make('comissao')
                    ->label('%')
                    ->numeric(),

                Tables\Columns\TextColumn::make('vlr_comissao')
                    ->label('Pr. Produtividade')
                    ->money('BRL')
                    ->formatStateUsing(fn($state)=>'R$'.number_format($state / 100, 2, ',', '.'))
                    ->summarize(Sum::make()->money('BRL', 100)),

                Tables\Columns\TextColumn::make('viagem.destino')
                    ->label('Destino')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->groups([
                Group::make('viagem.placa')
                    ->collapsible()
                    ->label('Placa'),
                Group::make('viagem.data')
                    ->collapsible()
                    ->label('Data'),
                Group::make('dupla')
                    ->collapsible()
                    ->label('Dupla'),
                    ])
            // ->groupsOnly()
            ->defaultGroup('dupla')
            ->filters([
                TernaryFilter::make('dupla')
                    ->nullable()
                    ->placeholder('Todos')
                    ->trueLabel('Com dupla')
                    ->falseLabel('Sem Dupla'),
                
            ])
            ->headerActions([
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }
}
