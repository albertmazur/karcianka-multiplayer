<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Game extends Model
{
    use HasFactory;

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function userFriend(): BelongsTo{
        return $this->belongsTo(User::class, 'send_user_id');
    }

    public function startGame(){
        $cardGame = CardGame::all();
        $cardGame = $cardGame->shuffle();

        $user1 = new Collection();
        $user2 = new Collection();

        for($i = 0; $i < count($cardGame); $i++){
            $c = new Card();
            $c->card_game_id = $cardGame[$i]->id;
            $c->game_id = $this->id;

            if($i < 10){
                $c->where = "user";

                if($i%2==0){
                    $c->user_id = $this->user_id;
                    $user1->add($c);
                }
                if($i%2==1){
                    $c->user_id = $this->send_user_id;
                    $user2->add($c);
                }
            }
            else if($i == 11){
                $c->where = "uncover";
                $uncoverCard = $c;
            }
            else $c->where = "cover";

            $c->save();
        }

        return[
            "start" => $this->id,
            "sum" => $this->sum,
            "whoNow" => $this->whoNow->nick,
            "user1" => $this->user1(),
            "user2" => $this->user2(),
            "uncover" => $uncoverCard->cardGame->card,
         ];
    }

    public function whoNow(): BelongsTo{
        return $this->belongsTo(User::class, 'who_now');
    }

    public function cards():HasMany{
        return $this->hasMany(Card::class);
    }

    public function user1(): Collection{
        return $this->cards()->with('cardGame')->where("where", "=", "user")->where("user_id", "=", $this->user_id)->get()->map(function (Card $card) {
            return $card->cardGame->card;
        });
    }

    public function user2(): Collection{
        return $this->cards()->with('cardGame')->where("where", "=", "user")->where("user_id", "=", $this->send_user_id)->get()->map(function (Card $card) {
            return $card->cardGame->card;
        });
    }

    public function coverCard(){
        $coverCard = $this->cards()->where("where", "=", "cover")->orderBy("updated_at", "DESC")->first();
        if($coverCard == null){
            $uncoverCards = $this->cards()->where("where", "=", "uncover")->orderBy("updated_at", "DESC")->get();
            $uncoverCards->shift();
            $uncoverCards->shuffle();
            $uncoverCards->transform(function (Card $card){
                $card->where = "cover";
                $card->update();
                return $card;
            });
            return $uncoverCards->first();
        }
        return $coverCard;
    }

    public function uncoverCard(){
        return $this->cards()->where("where", "=", "uncover")->orderBy("updated_at", "DESC")->first();
    }

    public function changeWhoNow($userId){
        if($userId == $this->user_id){
            $whoNow = $this->user->nick;
            $this->who_now = $this->user->id;
            $send = $this->user_id;
        }
        if($userId == $this->send_user_id){
            $whoNow = $this->userFriend->nick;
            $this->who_now = $this->userFriend->id;
            $send = $this->send_user_id;
        }
        $this->update();
        return ['whoNow' => $whoNow, 'send' => $send];
    }

    public function getCard($userId, $card){
        return $this->cards()->where("user_id", "=", $userId)->where("card_game_id", "=", CardGame::where("card", "=", $card )->first()->id)->first();
    }

    public function checkWin(){
        $countCover = $this->cards()->where("where", "=", "cover")->count();
        $uncountCover = $this->cards()->where("where", "=", "uncover")->count();
        if($this->user1()->count() == 0 || $this->user2()->count() == 0 || (( $countCover== 0 && $uncountCover== 1 || $countCover+($uncountCover-1) < $this->sum))){
            return true;
        }
        return false;
    }

    public function whoWin(){
        $countUser1 = $this->user1()->count();
        $countUser2 = $this->user2()->count();

        if($countUser1 == 0) return $this->userFriend->nick;
        if($countUser2 == 0) return $this->user->nick;

        if($countUser1 < $countUser2) return $this->userFriend->nick;
        if($countUser1 < $countUser2) return $this->user->nick;
    }

    public function addCard($userId){
        $card = $this->coverCard();
        $card->user_id = $userId;
        $card->where = "user";
        $card->update();
        return $card->cardGame->card;
    }

    public function checkSing($sing, $game){
        $sum = $game->sum;
        $f = true;
        switch($sing){
            case "02":
                $sum+=2;
                break;
            case "03":
                $sum+=3;
                break;
            case "0Q":
                $sum=0;
                break;
            case "0J":
                if($sum<=5){
                    $sum=0;
                }
                else{
                    $sum-=5;
                }
                break;
            case "0K":
                $sum+=5;
                break;
            case "0A":
                break;
            default:
                if($sum==0) $f = true;
                else $f = false;
        }
        $game->sum = $sum;
        $game->update();
        return $f;
    }

    public function finishGame(){
        $this->cards()->delete();
        $this->delete();
    }
}
