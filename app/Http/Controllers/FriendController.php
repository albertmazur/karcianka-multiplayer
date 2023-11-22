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
            'name' => 'required',
        ]);

        $user = User::where("name", "=", $validated['name'])->first();

            if($user){
                if($user->name == Auth::user()->name){
                    $info = ["error" =>  "Nie możesz podać samego siebie" ];
                }
                else{
                    if($this->friendRepository->sendInvitation(Auth::id(), $user->id)){
                        $info = ["success" =>  "Wysłano zaproszenie" ];
                    }
                    else{
                        $info = ["error" =>  "Jesteście znajomimi lub zaproszenie było już wysłane" ];
                    }
                }
            }
            else{
                $info = ["error" =>  "Nie ma takiego użytkownika" ];
            }
        return back()->with($info);
    }

    public function remove(Request $request){
        $date = $request->validate(["id" => ["required", "integer"]]);
        $this->friendRepository->remove($date["id"]);
        return back()->with(["success" => "Udało się usunać"]);
    }

    public function accepted(Request $request){
        $date = $request->validate(["id" => ["required", "integer"]]);
        $this->friendRepository->acceptedInvitation($date["id"]);
        return back()->with(["success" => "Dodano do znajomych"]);
    }

    public function notAccepted(Request $request){
        $date = $request->validate(["id" => ["required", "integer"]]);
        $this->friendRepository->notAcceptedInvitation($date["id"]);
        return back()->with(["success" => "Udało się usuanąć zaproszenie"]);
    }
}
