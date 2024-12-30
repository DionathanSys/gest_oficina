<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\MotivoAjudaEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplementoAcerto extends Model
{
    use HasFactory;

    protected $table = 'complementos_acerto';

    protected $casts = [
        'vlr_ajuda' => MoneyCast::class,
        'motivo' => MotivoAjudaEnum::class,
    ];

    public function acerto()
    {
        return $this->belongsTo(Acerto::class);
    }
}
