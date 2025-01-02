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

    public function propostas()
    {
        return $this->hasMany(PropostaCotacao::class, 'produto_id', 'produto_id')
                    ->when($this->cotacao_id, function ($query) {
                        $query->where('cotacao_id', $this->cotacao_id);
                    });
    }
}
