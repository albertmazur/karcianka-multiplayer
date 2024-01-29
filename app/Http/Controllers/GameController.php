<?php

namespace App\Http\Controllers;

use App\Events\GameBroadcast;
use App\Http\Requests\GetDataGame;
use App\Models\Game;
use App\Models\User;
use App\Repository\GameRepository;
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
        $game = $this->gameInvationRepository->get($date['id']);

        if($game != null) return view("game.multiplayer", ["user" => User::find($game->send_user_id), "game_id" => $date['id'], "start" => 1]);
        else return redirect()->route("game.start")->with(["error" => "Nie można dołaczyć do gry"]);
    }

    public function broadcast(GetDataGame $request){
        $date = $request->validated();
        $d = [];
        $game = Game::find($date["game"]);
        if(isset($date["start"]) && $date["start"]){
            if($game->cards()->count()==0){
                $d = $game->startGame();
                $d1 = $d;
                $d1["user2"] = count($d["user2"]);

                event(new GameBroadcast($game->send_user_id, $d1));

                $d2 = $d;
                $d2["user1"] = $d["user2"];
                $d2["user2"] = count($d["user1"]);
                $d = $d2;
            }
            else{
                $game->finishGame();
                return redirect()->route("game.start")->with(["error" => "Nie udało sie połączyć z tą grą. Spróbuj ponownie"]);
            }
        }

        if(isset($date["card"])){
            $ddd = $game->changeWhoNow($date["userId"]);
            $whoNow = $ddd['whoNow'];
            $send = $ddd['send'];

            if($date["card"] == "add"){
                if($game->checkWin()){
                    $d = ["card" => null, "win" => $game->whoWin()];
                    $game->finishGame();
                }
                else{
                    $cards = [];
                    for($i = 1; $i < $game->sum; $i++) $cards[] = $game->addCard($date['userId']);
                    $cards[] = $game->addCard($date['userId']);

                    $game->sum = 0;
                    $game->update();

                    $d = ["card" => $cards, "whoNow" => $whoNow];
                }

                event(new GameBroadcast($send, ["card" => "add", "count" => count($cards), "whoNow" => $whoNow, "sum" => 0]));
            }
            else{
                $card = $game->getCard($date["userId"], $date["card"]);
                $cardGame = $card->cardGame;

                $uncoverCardGame = $game->uncoverCard()->cardGame;

                $selectedCardSign = substr($cardGame->card, 0, 2);
                $selectedCardFigure = substr($cardGame->card, 3, strlen($cardGame->card));
                $uncoverCardSign = substr($uncoverCardGame->card, 0, 2);
                $uncoverCardFigure = substr($uncoverCardGame->card, 3, strlen($uncoverCardGame->card));

                if(($selectedCardSign==$uncoverCardSign || $selectedCardFigure==$uncoverCardFigure) && $game->checkSing($selectedCardSign, $game)){

                    $card->user_id = null;
                    $card->where = "uncover";
                    $card->update();
                    $d = ["card" => $cardGame->card, "whoNow" => $whoNow, "sum" => $game->sum];

                    if($game->checkWin()){
                        $d = ["card" => $cardGame->card, "win" => $game->whoWin()];
                        $game->finishGame();
                    }

                    event(new GameBroadcast($send, $d));
                }
            }
        }
        return response()->json($d);
    }
}
