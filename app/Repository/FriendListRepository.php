<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;

interface FriendListRepository{
    public function sendInvitation(int $idAuth, int $user): void;
    public function myFriends(int $idUser): Collection;
    public function listInvitation(int $idAuth): Collection;
    public function acceptedInvitation(int $idMyFrend): void;
    public function notAcceptedInvitation(int $idMyFrend): void;
    public function remove(int $idUser);
}
