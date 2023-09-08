<?php

namespace App\Providers;

use App\Enums\Roles;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::define('admin', fn($user) => auth()->user()->role == Roles::ADMIN);
        Gate::define('has-post', fn($user, $post) => $user->id == $post->user_id);
        Gate::define('has-comment', fn($user, $comment) => $user->id == $comment->user_id);

        // Observers
        User::observe(UserObserver::class);
    }
}
