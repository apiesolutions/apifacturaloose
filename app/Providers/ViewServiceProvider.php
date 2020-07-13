<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'layouts.app',
            'App\Http\ViewComposers\CompanyViewComposer'
        );

        view()->composer(
            'layouts.app',
            'App\Http\ViewComposers\UserViewComposer'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}