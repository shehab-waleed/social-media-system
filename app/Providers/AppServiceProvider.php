<?php

namespace App\Providers;

use App\Models\Post;
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

        Gate::define('has-post', function ($user, $post) {
            return $user->id == $post->user_id;
        });

        Gate::define('has-comment', function ($user, $comment) {
            return $user->id == $comment->user_id;
        });

        Gate::define('admin', function ($user) {
            return auth()->user()->is_admin;
        });

        // Observers
        User::observe(UserObserver::class);
    }

}
