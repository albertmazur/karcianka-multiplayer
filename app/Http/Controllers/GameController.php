<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function start(): View{
        return view('game.index');
    }

    public function stats(): View{
        return view('game.stats');
    }
}
