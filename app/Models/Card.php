<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    use HasFactory;

    public function cardGame(): BelongsTo{
        return $this->belongsTo(CardGame::class);
    }

    public function game(): BelongsTo{
        return $this->belongsTo(Game::class);
    }
}
