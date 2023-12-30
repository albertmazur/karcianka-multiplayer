<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Game extends Model
{
    use HasFactory;

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function userFriend(): BelongsTo{
        return $this->belongsTo(User::class, 'send_user_id');
    }

    public function whoNow(): BelongsTo{
        return $this->belongsTo(User::class, 'who_now');
    }

    public function cards():HasMany{
        return $this->hasMany(Card::class);
    }

    public function user1(): Collection{
        return $this->cards()->with('cardGame')->where("user_id", "=", $this->user_id)->get()->map(function (Card $card) {
            return $card->cardGame->card;
        });
    }

    public function user2(): Collection{
        return $this->cards()->with('cardGame')->where("user_id", "=", $this->send_user_id)->get()->map(function (Card $card) {
            return $card->cardGame->card;
        });
    }

    public function coverCard(){
        return $this->cards()->where("where", "=", "cover")->orderBy("updated_at")->first();
    }

    public function uncoverCard(){
        return $this->cards()->where("where", "=", "uncover")->orderBy("updated_at", "DESC")->first();
    }
}
