<?php

namespace App\Models\Parceiro;

use App\Models\PropostaCotacao;
use App\Models\Protocolo\Documento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fornecedor extends Model
{
    use HasFactory;

    protected $table = 'fornecedores';

    public function documentos():HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function propostas_cotacao():HasMany
    {
        return $this->hasMany(PropostaCotacao::class);
    }
}
