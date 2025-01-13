<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemManutencao extends Model
{
    use HasFactory;

    protected $table = 'itens_manutencao';

    public function anotacoes(): HasMany
    {
        return $this->hasMany(AnotacaoVeiculo::class, 'item_manutencao_id');
    }
}
