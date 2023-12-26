<?php

namespace App\Http\Controllers;

use App\Events\GameBroadcast;
use App\Models\Card;
use App\Models\CardGame;
use App\Models\Game;
use App\Models\User;
use App\Repository\GameRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GameController extends Controller{

    private GameRepository $gameInvationRepository;

    public function __construct(GameRepository $gameInvationRepository){
        $this->gameInvationRepository = $gameInvationRepository;
    }

    public function start(): View{
        return view("game.index", [
            "friends" => $this->gameInvationRepository->listFriends(Auth::id()),
            "games" => $this->gameInvationRepository->listGames(Auth::id())
        ]);
    }

    public function single(): View{
        return view('game.single');
    }

    public function multiplayer(Request $request){
        $date = $request->validate(["id" => ["required", "integer"]]);
        $game = $this->gameInvationRepository->add(Auth::id(), $date["id"]);

        if($game == true) return view("game.multiplayer", ["user" => User::find($game->user_id)]);
        else return back()->with(["error" => "Nie uruchomiono gry"]);
    }

    public function join(Request $request){
        $date = $request->validate(["id" => ["required", "integer"]]);
        $game = Game::find($date['id']);

        if($game == true) return view("game.multiplayer", ["user" => User::find($game->send_user_id), "game_id" => $date['id']]);
        else return back()->with(["error" => "Nie można dołaczyć do gry"]);
    }

    public function broadcast(Request $request){
        $date = $request->validate([
            "userId" => ["required", "integer"],
            "message" => ["string"],
        ]);

        if(is_int((int) $date["message"])){
           $game = Game::find((int) $date["message"]);
           $d = $this->startGame($game);
            event(new GameBroadcast($game->user_id, $d));
            event(new GameBroadcast($game->send_user_id, $d));
        }
        else{

        }
        return response()->json(['status' => 'Message sent!']);
    }

    private function startGame($game){
        $cardGame = CardGame::all();

        for($i = 0; $i< count($cardGame); $i++){
            $c = new Card();
            $c->card_game_id = $cardGame[$i]->id;
            $c->game_id = $game->id;
            if($i< 20){
                if($i%2==0) $c->user_id = $game->user_id;
                if($i%2==1) $c->user_id = $game->send_user_id;

                $c->where = "user";
            }
            if($i == 21) $c->where = "uncover";
            else $c->where = "cover";

            $c->save();
            return [
               "start" => $game->id,
               "user1" => CardGame::where("game_id", "=", $game->id)->where("user")->get(),
               "user1" => CardGame::where("game_id", "=", $game->id)->where()->get(),
               "cover" => CardGame::where("game_id", "=", $game->id)->where("where", "=", "cover")->get(),
               "uncover" => CardGame::where("game_id", "=", $game->id)->where("where", "=", "uncover")->get(),
            ];
        }
    }
}
