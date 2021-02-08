<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Observers\RecipeObserver;
use App\Recipe;






class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('pagination::default');

        Blade::if('admin', function () {
            return Auth::check() && auth()->user()->isAdmin();
        });

        Relation::morphMap([
            'ingredient' => 'App\Ingredient',
            'recipe' => 'App\Recipe',
        ]);

        Recipe::observe(RecipeObserver::class);

    }
}
