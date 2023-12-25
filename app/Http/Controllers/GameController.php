<?php

namespace App\Http\Controllers;

use App\Events\GameBroadcast;
use App\Repository\GameInvationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GameController extends Controller{

    private GameInvationRepository $gameInvationRepository;

    public function __construct(GameInvationRepository $gameInvationRepository){
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
        $f = $this->gameInvationRepository->add(Auth::id(), $date["id"]);

        if($f == true) return view('game.multiplayer');
        else return back()->with(["error" => "Nie uruchomiono gry"]);
    }

    public function join(Request $request){
        $date = $request->validate(["id" => ["required", "integer"]]);
        $f = $this->gameInvationRepository->remove(Auth::id(), $date["id"]);

        if($f == true) return view('game.multiplayer', ["start" => "Udało się"]);
        else return back()->with(["error" => "Nie można dołaczyć do gry"]);
    }

    public function broadcast(Request $request){
        event(new GameBroadcast(Auth::user(), 'Test dla aaa'));

        return response()->json(['status' => 'Message sent!']);
    }

    public function receive(Request $request){
        return view('game.multiplayer', ['message'=> $request->get('message')]);
    }
}
