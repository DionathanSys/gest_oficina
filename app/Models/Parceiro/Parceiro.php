<?php

namespace App\Models\Parceiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'boolean',
    ];

}
