<?php

namespace App\Models;

use App\Models\Parceiro\Fornecedor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoCotacao extends Model
{
    use HasFactory;

    protected $table = 'produtos_cotacoes';

    public function cotacao()
    {
        return $this->belongsTo(Cotacao::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function proposta()
    {
        return $this->hasMany(PropostaCotacao::class);
    }
}
