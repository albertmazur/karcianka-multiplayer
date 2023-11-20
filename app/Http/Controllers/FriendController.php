<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index():View{
        $friends = User::find(Auth::id())->friends();
        return view("friend.index", ["friends" => $friends]);
    }

    public function add(Request $request){
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $user = User::where("name", "=", $validated['name'])->get();
        return back()->with();
    }
}
