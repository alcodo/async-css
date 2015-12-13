<?php namespace Alcodo\CssAsync;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {

        $this->app->singleton('CssAsync', function ($app) {
            return new CssAsync();
        });
    }

    public function boot()
    {
    }
}