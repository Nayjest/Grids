<?php namespace Nayjest\Grids;

use Illuminate\Foundation\Application;
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
     * For backward compatibility with Laravel 4
     * @deprecated
     * @return string
     */
    public function guessPackagePath()
    {
        return __DIR__;
    }
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        # only for Laravel 4 & some of 5-dev
        if (version_compare(Application::VERSION, '5.0.0', '<')) {
            $this->package('nayjest/grids');
        }
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
