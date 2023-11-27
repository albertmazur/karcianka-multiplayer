<?php

namespace App\Providers;

use App\Repository\FriendListRepository;
use App\Repository\Eloquent\FriendListRepository as EloquentFriendListRepositor;
use Illuminate\Support\ServiceProvider;

class FriendListServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(FriendListRepository::class, EloquentFriendListRepositor::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
