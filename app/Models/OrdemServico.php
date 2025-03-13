<?php

namespace App\Models;

use App\Enums\StatusDiversos;
use App\Enums\StatusOrdemSankhya;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    use HasFactory;

    protected $table = 'ordens_servico';

    protected $casts = [
        'status' => StatusDiversos::class,
        'status_sankhya' => StatusOrdemSankhya::class,
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    public function itens()
    {
        return $this->hasMany(ItemOrdemServico::class, 'ordem_servico_id');
    }

}
