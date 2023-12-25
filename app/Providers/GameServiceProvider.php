<?php

namespace App\Providers;

use App\Repository\GameInvationRepository;
use App\Repository\Eloquent\GameInvationRepository as EloquentGameInvationRepositor;
use Illuminate\Support\ServiceProvider;

class GameServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(GameInvationRepository::class, EloquentGameInvationRepositor::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
