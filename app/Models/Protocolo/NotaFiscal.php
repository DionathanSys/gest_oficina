<?php

namespace App\Models\Protocolo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaFiscal extends Model
{
    use HasFactory;

    protected $table = 'notas_fiscais';

    public function documento():BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }
}
