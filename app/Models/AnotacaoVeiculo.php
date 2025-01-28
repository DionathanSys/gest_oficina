<?php

namespace App\Models;

use App\Enums\{Prioridade, StatusDiversos, TipoAnotacao};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnotacaoVeiculo extends Model
{
    use HasFactory;

    protected $cast = [
        'status' => StatusDiversos::class,
        'prioridade' => Prioridade::class,
        'tipo_anotacao' => TipoAnotacao::class,
    ];

    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class);
    }

    public function itemManutencao(): BelongsTo
    {
        return $this->belongsTo(ItemManutencao::class, 'item_manutencao_id');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioAnotacao::class);
    }
}
