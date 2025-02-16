<?php

namespace App\Models;

use App\Enums\StatusCotacaoEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cotacao extends Model
{
    use HasFactory;

    protected $table = 'cotacoes';

    protected $casts = [
        'status' => StatusCotacaoEnum::class,
    ];

    public function produtos_cotacao():HasMany
    {
        return $this->hasMany(ProdutoCotacao::class);
    }
   
    public function propostas_cotacao():HasMany
    {
        return $this->hasMany(PropostaCotacao::class);
    }

    public function produto():BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
