<?php

namespace App\Repository\Eloquent;

use App\Models\FriendList;
use App\Models\User;
use App\Repository\FriendListRepository as Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class FriendListRepository implements Repository{
    private FriendList $friendListModel;

    public function __construct(FriendList $friendList){
        $this->friendListModel = $friendList;
    }

    public function sendInvitation(int $user): bool{
        $idAuth = Auth::id();
        $invitation = $this->friendListModel
        ->where(function($query) use ($idAuth, $user) {
            $query->where('user_id', '=', $idAuth)
                  ->where('user_friend_id', '=', $user);
        })
        ->orWhere(function($query) use ($idAuth, $user) {
            $query->where('user_id', '=', $user)
                  ->where('user_friend_id', '=', $idAuth);
        })
        ->first();

        if($invitation == null){
            $newFriend = new FriendList();

            $newFriend->user_id = $user;
            $newFriend->user_friend_id = $idAuth;
            $newFriend->accepted = false;
            $newFriend->save();
            return true;
        }
        return false;
    }

    public function acceptedInvitation(int $idMyFriend): bool{
        $friend = $this->getOne($idMyFriend);

        if($friend != null ){
            $friend->accepted = true;
            $friend->save();
            return true;
        }
        return false;
    }

    public function notAcceptedInvitation(int $idMyFriend): bool{
        $friend = $this->getOne($idMyFriend);

        if($friend != null ){
            $friend->delete();
            return true;
        }
        return false;
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
        return $this->friendListModel->where("user_id", '=', $idUser)->orWhere("user_friend_id", '=', $idUser)->delete();
    }

    public function get(int $idUser): FriendList{
        return $this->friendListModel->where("user_id", '=', $idUser)->first();
    }

    public function listInvitation(int $idAuth): Collection{
        return $this->friendListModel->where("user_id", "=", $idAuth)->where('accepted', '=', false)->get();
    }

    private function getOne($idMyFriend): FriendList{
        $idAuth = Auth::id();
        return $this->friendListModel
        ->where(function($query) use ($idAuth, $idMyFriend) {
            $query->where('user_id', '=', $idAuth)
                  ->where('user_friend_id', '=', $idMyFriend);
        })
        ->orWhere(function($query) use ($idAuth, $idMyFriend) {
            $query->where('user_id', '=', $idMyFriend)
                  ->where('user_friend_id', '=', $idAuth);
        })
        ->first();
    }
}
