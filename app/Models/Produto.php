<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    use HasFactory;

    public function cotacoes():HasMany
    {
        return $this->hasMany(Cotacao::class);
    }

    public function produtos_cotacao()
    {
        return $this->hasMany(ProdutoCotacao::class);
    }
}
