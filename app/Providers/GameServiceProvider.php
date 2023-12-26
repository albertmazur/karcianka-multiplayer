<?php

namespace App\Providers;

use App\Repository\GameRepository;
use App\Repository\Eloquent\GameRepository as EloquentGameRepositor;
use Illuminate\Support\ServiceProvider;

class GameServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(GameRepository::class, EloquentGameRepositor::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
