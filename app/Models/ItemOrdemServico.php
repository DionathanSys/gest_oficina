<?php

namespace App\Models;

use App\Enums\StatusDiversos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ItemOrdemServico extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => StatusDiversos::class,
    ];
    
    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class);
    }

    public function itemManutencao():BelongsTo
    {
        return $this->belongsTo(ItemManutencao::class);
    }

    public function veiculo(): HasOneThrough
    {
        return $this->hasOneThrough(Veiculo::class, OrdemServico::class, 'id', 'id', 'ordem_servico_id', 'veiculo_id');
    }
}
