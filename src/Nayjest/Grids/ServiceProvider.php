<?php namespace Nayjest\Grids;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Route;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        # only for Laravel 4
        if (method_exists($this, 'package')) {
            $this->package('nayjest/grids');
        }
        Route::controller('grids', 'Nayjest\Grids\Controller');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
