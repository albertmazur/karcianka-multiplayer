<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repository\FriendListRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FriendController extends Controller
{
    private FriendListRepository $friendRepository;

    public function __construct(FriendListRepository $friendRepository){
        $this->friendRepository = $friendRepository;
    }

    public function index():View{
        return view("friend.index", [
            "friends" => $this->friendRepository->myFriends(Auth::id()),
            "invitations" => $this->friendRepository->listInvitation(Auth::id())
        ]);
    }

    public function sendInvitation(Request $request){
        $validated = $request->validate([
            'nick' => 'required',
        ]);

        $user = User::where("nick", "=", $validated['nick'])->first();

            if($user){
                if($user->nick == Auth::user()->nick) $info = ["error" =>  "Nie możesz podać samego siebie" ];
                else{
                    if($this->friendRepository->sendInvitation($user->id)) $info = ["success" =>  "Wysłano zaproszenie" ];
                    else $info = ["error" =>  "Jesteście znajomimi lub zaproszenie było już wysłane" ];
                }
            }
            else $info = ["error" =>  "Nie ma takiego użytkownika" ];

        return back()->with($info);
    }

    public function remove(Request $request){
        $date = $request->validate(["id" => ["required", "integer"]]);
        $friendList = $this->friendRepository->remove($date["id"]);
        if ($friendList) {
            return back()->with('success', 'Usunięto znalomego z listy znajomych');
        }
        return back()->with('error', 'Nie ma kiatego znajomego');
    }


    public function accepted(Request $request){
        $date = $request->validate(["id" => ["required", "integer"]]);
        $f = $this->friendRepository->acceptedInvitation($date["id"]);
        if($f) return back()->with(["success" => "Dodano do znajomych"]);
        else return back()->with(["error" => "Nie udało dodać do znajomych"]);
    }

    public function notAccepted(Request $request){
        $date = $request->validate(["id" => ["required", "integer"]]);
        $f = $this->friendRepository->notAcceptedInvitation($date["id"]);
        if($f) return back()->with(["success" => "Udało się usuanąć zaproszenie"]);
        else return back()->with(["error" => "Nie udało sie usunąć zaproszenia"]);
    }
}
