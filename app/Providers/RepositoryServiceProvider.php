<?php

namespace App\Providers;


use App\Models\User;
use App\Repositories\Contracts\UserInterface;
use App\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, fn() => new EloquentUserRepository(new User()));


    }
}
