<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        \Schema::defaultStringLength(190);

        /*if (setlocale(LC_ALL, "pl_PL.u tf8") === false) {
            throw new \Exception('Can\'t set locale. Check if system have pl_pl.utf8 locales installed.');
        }*/

        $loggedUsersLayouts = ['user', 'admin'];


        foreach ($loggedUsersLayouts as $layout) {

            \View::composer(
                'layouts.' . $layout,
                function ($view) {
                    $view->with('user', \Auth::user());
                }
            );

        }
        // Force ssl shema when we are behind haproxy
        if ($this->app->environment() === "production") {
            \URL::forceScheme('https');
        }
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
