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

    public function fornecedor ():BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }
}
