<?php

namespace App\Repository;

use App\Models\FriendList;
use Illuminate\Database\Eloquent\Collection;

interface FriendListRepository{
    public function sendInvitation(int $idAuth, int $user): bool;
    public function myFriends(int $idUser): Collection;
    public function listInvitation(int $idAuth): Collection;
    public function acceptedInvitation(int $idMyFrend): void;
    public function notAcceptedInvitation(int $idMyFrend): void;
    public function remove(int $idUser);
    public function get(int $idUser): FriendList;
}
