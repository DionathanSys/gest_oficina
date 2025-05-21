<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Manager extends Model
{
    use HasFactory;

    public function indicators(): BelongsToMany
    {
        return $this->belongsToMany(Indicator::class);
    }

}
