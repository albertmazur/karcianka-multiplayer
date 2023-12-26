<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface GameInvationRepository{
    public function add(int $idAuth, int $user): User;
    public function remove(int $idAuth, int $user): User;
    public function listGames(int $idAuth): Collection;
    public function listFriends(int $idAuth): Collection;
}
