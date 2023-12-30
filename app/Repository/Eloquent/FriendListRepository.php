<?php

namespace App\Repository\Eloquent;

use App\Models\FriendList;
use App\Models\User;
use App\Repository\FriendListRepository as Repository;
use Illuminate\Database\Eloquent\Collection;

class FriendListRepository implements Repository{
    private FriendList $friendListModel;

    public function __construct(FriendList $friendList){
        $this->friendListModel = $friendList;
    }

    public function sendInvitation(int $idAuth, int $user): bool{
        $invitation1 = $this->friendListModel->where('user_id', '=', $idAuth)->where('user_friend_id', '=', $user)->first();
        $invitation2 = $this->friendListModel->where('user_id', '=', $user)->where('user_friend_id', '=', $idAuth)->first();

        if($invitation1 == null && $invitation2 == null){
            $newFriend = new FriendList();

            $newFriend->user_id = $user;
            $newFriend->user_friend_id = $idAuth;
            $newFriend->accepted = false;
            $newFriend->save();
            return true;
        }
        return false;
    }

    public function acceptedInvitation(int $idMyFriend): void{
        $user = $this->friendListModel->where('user_friend_id', '=', $idMyFriend)->first();
        $user->accepted = true;
        $user->save();
    }

    public function notAcceptedInvitation(int $idMyFriend): void{
        $user = $this->friendListModel->where('user_friend_id', '=', $idMyFriend)->first();
        $user->delete();
    }

    public function myFriends(int $idUser): Collection {
        $friendIds = $this->friendListModel
            ->where('user_id', $idUser)
            ->where('accepted', true)
            ->pluck('user_friend_id')
            ->merge(
                $this->friendListModel
                    ->where('user_friend_id', $idUser)
                    ->where('accepted', true)
                    ->pluck('user_id')
            )->unique();

        return User::whereIn('id', $friendIds)->get();
    }

    public function remove(int $idUser){
        $friend =$this->friendListModel->where("user_id", '=', $idUser)->first();
        $friend->delete();
    }

    public function get(int $idUser): FriendList{
        return $this->friendListModel->where("user_id", '=', $idUser)->first();
    }

    public function listInvitation(int $idAuth): Collection{
        return $this->friendListModel->where("user_id", "=", $idAuth)->where('accepted', '<>', true)->get();
    }
}
