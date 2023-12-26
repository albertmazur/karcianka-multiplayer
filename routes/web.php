<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\FriendController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/game', [GameController::class, 'start'])->name('game.start');
    Route::get('/game/stats', [GameController::class, 'single'])->name('game.stats');

    Route::get('friend', [FriendController::class, 'index'])->name('friend.index');
    Route::put('friend', [FriendController::class, 'sendInvitation'])->name('friend.add');
    Route::delete('friend', [FriendController::class, 'remove'])->name('friend.remove');

    Route::post('friend/accepted', [FriendController::class, 'accepted'])->name('friend.accepted');
    Route::delete('friend/not-accepted', [FriendController::class, 'notAccepted'])->name('friend.not-accepted');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
