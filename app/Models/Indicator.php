<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Indicator extends Model
{
    use HasFactory;

    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(Manager::class);
        // return dd($this->belongsToMany(Manager::class)->toSql());
    }
}
