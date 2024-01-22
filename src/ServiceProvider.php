<?php namespace Nayjest\Grids;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * This method required for backward compatibility with Laravel 4.
     *
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
        $pkg_path = dirname(__DIR__);
        $views_path = $pkg_path.'/resources/views';

        $this->loadViewsFrom($views_path, 'grids');
        $this->loadTranslationsFrom(realpath(__DIR__.'/../resources/lang'), 'grids');
        $this->publishes([
            $views_path => base_path('resources/views/vendor/grids'),
        ]);

        if (!class_exists('Grids')) {
            class_alias('\\Nayjest\\Grids\\Grids', '\\Grids');
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
