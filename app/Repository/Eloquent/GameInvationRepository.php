<?php

namespace App\Repository\Eloquent;

use App\Models\GameInvation;
use App\Models\FriendList;
use App\Models\User;
use App\Repository\GameInvationRepository as Repository;
use Illuminate\Database\Eloquent\Collection;

class GameInvationRepository implements Repository{
    private GameInvation $gameInvationModel;
    private FriendList $friendListModel;

    public function __construct(GameInvation $gameInvation, FriendList $friendList){
        $this->gameInvationModel = $gameInvation;
        $this->friendListModel = $friendList;
    }

    public function remove(int $idAuth, int $user):bool{
        $game = $this->gameInvationModel->where("user_id", '=', $idAuth)->where("send_user_id", '=', $user)->first();
        if($game != null){
            $game->delete();
            return true;
        }
        else return false;

    }

    public function listGames(int $idAuth): Collection {
        return $this->gameInvationModel->where("user_id", "=", $idAuth)->get();
    }

    public function listFriends(int $idAuth): Collection {
        $games = $this->gameInvationModel->where("user_id", "=", $idAuth)->get();

        $friendIds = $this->friendListModel
            ->where('user_id', $idAuth)
            ->where('accepted', true)
            ->pluck('user_friend_id')
            ->merge(
                $this->friendListModel
                    ->where('user_friend_id', $idAuth)
                    ->where('accepted', true)
                    ->pluck('user_id')
            )->unique();

        foreach ($games as $game) {
            $friendIds = $friendIds->reject(function ($friendId) use ($game) {
                return $friendId == $game->send_user_id;
            });
        }

        return User::whereIn('id', $friendIds)->get();
    }

    public function add(int $idAuth, int $user): bool{
        $invitation1 = $this->gameInvationModel->where('user_id', '=', $idAuth)->where('send_user_id', '=', $user)->first();
        $invitation2 = $this->gameInvationModel->where('user_id', '=', $user)->where('send_user_id', '=', $idAuth)->first();

        if($invitation1 == null && $invitation2 == null){
            $newGame = new GameInvation();

            $newGame->user_id = $user;
            $newGame->send_user_id = $idAuth;
            $newGame->accepted = false;
            $newGame->save();
            return true;
        }
        return false;
    }
}
