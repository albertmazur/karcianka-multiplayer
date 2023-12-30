<?php

namespace App\Http\Controllers;

use App\Events\GameBroadcast;
use App\Http\Requests\GetDataGame;
use App\Models\Card;
use App\Models\CardGame;
use App\Models\Game;
use App\Models\User;
use App\Repository\GameRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GameController extends Controller{

    private GameRepository $gameInvationRepository;

    public function __construct(GameRepository $gameInvationRepository){
        $this->gameInvationRepository = $gameInvationRepository;
    }

    public function start(): View{
        return view("game.index", [
            "friends" => $this->gameInvationRepository->listFriends(Auth::id()),
            "games" => $this->gameInvationRepository->listLatestGames(Auth::id())
        ]);
    }

    public function single(): View{
        return view('game.single');
    }

    public function multiplayer(Request $request){
        $date = $request->validate(["id" => ["required", "integer"]]);
        $game = $this->gameInvationRepository->add(Auth::id(), $date["id"]);

        return view("game.multiplayer", ["user" => User::find($game->user_id), "game_id" => $game->id]);
    }

    public function join(Request $request){
        $date = $request->validate(["id" => ["required", "integer"]]);
        $game = Game::find($date['id']);

        if($game == true) return view("game.multiplayer", ["user" => User::find($game->send_user_id), "game_id" => $date['id'], "start" => 1]);
        else return back()->with(["error" => "Nie można dołaczyć do gry"]);
    }

    public function broadcast(GetDataGame $request){
        $date = $request->validated();
        $d = [];
        $game = Game::find($date["game"]);
        if(isset($date["start"]) && $date["start"]){
            if($game->cards()->count()==0){
                $d = $this->startGame($game);
                $d1 = $d;
                $d1["user2"] = count($d["user2"]);

                event(new GameBroadcast($game->send_user_id, $d1));

                $d2 = $d;
                $d2["user1"] = $d["user2"];
                $d2["user2"] = count($d["user1"]);
                $d = $d2;
            }
            else{
                $d = ['messager' =>"Są karty"];
            }
        }

        if(isset($date["card"])){
            if($date["userId"] == $game->user_id){
                $whoNow = $game->user->nick;
                $game->who_now = $game->user->id;
                $send = $game->user_id;
            }
            if($date["userId"] == $game->send_user_id){
                $whoNow = $game->userFriend->nick;
                $game->who_now = $game->userFriend->id;
                $send = $game->send_user_id;
            }
            $game->update();

            if($date["card"] == "add"){
                $cards = [];
                for($i = 1; $i < $game->sum; $i++){
                    $cards[] = $this->addCard($game, $date['userId']);
                }
                $cards[] = $this->addCard($game, $date['userId']);

                $d = ["card" => $cards, "whoNow" => $whoNow];
                event(new GameBroadcast($send, ["card" => "add", "count" => count($cards), "whoNow" => $whoNow, "sum" => 0]));
            }
            else{
                $card = Card::where("game_id", "=", $game->id)->where("user_id", "=", $date["userId"])->where("card_game_id", "=", CardGame::where("card", "=", $date["card"] )->first()->id)->first();
                $cardGame = $card->cardGame;

                $uncoverCardGame = $game->uncoverCard()->cardGame;

                $selectedCardSign = substr($cardGame->card, 0, 2);
                $selectedCardFigure = substr($cardGame->card, 3, strlen($cardGame->card));
                $uncoverCardSign = substr($uncoverCardGame->card, 0, 2);
                $uncoverCardFigure = substr($uncoverCardGame->card, 3, strlen($uncoverCardGame->card));

                if(($selectedCardSign==$uncoverCardSign || $selectedCardFigure==$uncoverCardFigure) && $this->checkSing($selectedCardSign, $game)){

                    $card->user_id = null;
                    $card->where = "uncover";
                    $card->update();
                    $d = ["card" => $cardGame->card, "whoNow" => $whoNow, "sum" => $game->sum];

                    if($game->checkWin()){
                        $d = ["card" => $cardGame->card, "win" => $game->whoWin()];
                    }

                    event(new GameBroadcast($send, $d));
                }
            }

        }
        return response()->json($d);
    }

    private function startGame($game){
        $cardGame = CardGame::all();
        $user1 = new Collection();
        $user2 = new Collection();

        for($i = 0; $i < count($cardGame); $i++){
            $c = new Card();
            $c->card_game_id = $cardGame[$i]->id;
            $c->game_id = $game->id;

            if($i < 10){
                $c->where = "user";

                if($i%2==0){
                    $c->user_id = $game->user_id;
                    $user1->add($c);
                }
                if($i%2==1){
                    $c->user_id = $game->send_user_id;
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
            "start" => $game->id,
            "sum" => $game->sum,
            "whoNow" => $game->whoNow->nick,
            "user1" => $game->user1(),
            "user2" => $game->user2(),
            "uncover" => $uncoverCard->cardGame->card,
         ];
    }

    private function checkSing($sing, $game){
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
        $game->sum =$sum;
        $game->update();
        return $f;
    }

    private function addCard($game, $userId){
        $card = $game->coverCard();
        $card->user_id = $userId;
        $card->where = "user";
        $card->update();
        return $card->cardGame->card;
    }
}
