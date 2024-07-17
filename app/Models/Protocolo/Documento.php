<?php

namespace App\Models\Protocolo;

use App\Models\Parceiro\Fornecedor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Documento extends Model
{
    use HasFactory;

    public function fornecedor():BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function notasFiscais():HasMany
    {
        return $this->hasMany(NotaFiscal::class);
    }
}
