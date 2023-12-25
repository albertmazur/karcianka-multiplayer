<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;

interface GameInvationRepository{
    public function add(int $idAuth, int $user): bool;
    public function listGames(int $idAuth): Collection;
    public function listFriends(int $idAuth): Collection;
    public function remove(int $idAuth, int $user):bool;
}
