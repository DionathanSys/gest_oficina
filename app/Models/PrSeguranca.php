<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrSeguranca extends Model
{
    use HasFactory;

    protected $casts = [
        'premio' => MoneyCast::class,
    ];

    public function acerto()
    {
        return $this->belongsTo(Acerto::class);
    }
}
