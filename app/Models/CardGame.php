<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CardGame extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function card(): HasMany{
        return $this->hasMany(Card::class);
    }
}
