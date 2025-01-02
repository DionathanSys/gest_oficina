<?php

namespace App\Models;

use App\Models\Parceiro\Fornecedor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropostaCotacao extends Model
{
    use HasFactory;

    protected $table = 'propostas_cotacao';

    public function cotacao():BelongsTo
    {
        return $this->belongsTo(Cotacao::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);  
    }

    public function produtoCotacao()
    {
        return $this->belongsTo(ProdutoCotacao::class, 'produto_id', 'produto_id')
                        ->when($this->cotacao_id, function ($query) {
                            $query->where('cotacao_id', $this->cotacao_id);
                        });
    }

    public function fornecedor ():BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }
}
