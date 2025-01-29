<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComentarioAnotacao extends Model
{
    use HasFactory;

    public function anotacao(): BelongsTo
    {
        return $this->belongsTo(AnotacaoVeiculo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
