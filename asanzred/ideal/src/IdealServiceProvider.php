<?php

namespace Asanzred\Ideal;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class IdealServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // modify this if you want disable tutorial routes
        $this->setupRoutes($this->app->router);
        
        
        //php artisan vendor:publish --provider="Asanzred\Ideal\IdealServiceProvider"
        $this->publishes([
                __DIR__.'/config/ideal.php' => config_path('ideal.php'),
        ]);
        
        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            __DIR__.'/config/ideal.php', 'ideal'
        );
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Asanzred\Ideal\Http\Controllers'], function($router)
        {
            require __DIR__.'/Http/routes.php';
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerIdeal();
        
        //use this if your package has a config file
        config([
                'config/ideal.php',
        ]);
    }

    private function registerIdeal()
    {
        $this->app->bind('ideal',function($app){
            return new Ideal($app);
        });
    }
}