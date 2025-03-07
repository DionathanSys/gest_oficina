<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ComentarioAnotacao extends Model
{
    use HasFactory;

    public function anotacao(): BelongsTo
    {
        return $this->belongsTo(AnotacaoVeiculo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function veiculo(): HasOneThrough
    {
        return $this->hasOneThrough(Veiculo::class, AnotacaoVeiculo::class, 'id', 'id', 'anotacao_veiculo_id', 'veiculo_id');
    }

    public function itemManutencao(): HasOneThrough
    {
        return $this->hasOneThrough(ItemManutencao::class, AnotacaoVeiculo::class, 'id', 'id', 'anotacao_veiculo_id', 'item_manutencao_id');
    }
}
