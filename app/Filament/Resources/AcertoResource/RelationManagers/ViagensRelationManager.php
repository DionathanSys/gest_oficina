<?php

namespace App\Filament\Resources\AcertoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
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
                Tables\Columns\TextColumn::make('nro_nota'),
                Tables\Columns\TextColumn::make('motorista'),
                Tables\Columns\TextColumn::make('frete')
                    ->money('BRL')
                    ->summarize(Sum::make()->money('BRL')),

                Tables\Columns\TextColumn::make('comissao')
                    ->label('%')
                    ->numeric(),

                Tables\Columns\TextColumn::make('vlr_comissao')
                    ->label('Pr. Produtividade')
                    ->money('BRL')
                    ->summarize(Sum::make()->money('BRL', 100)),
            ])
            ->groups([
                Group::make('motorista')->collapsible()])
            // ->groupsOnly()
            ->defaultGroup('motorista')
            ->filters([
                Filter::make('dupla')
                    ->label('Sem Dupla')
                    ->query(fn(Builder $query):Builder => $query->where('dupla', null))
            ])
            ->headerActions([
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }
}
