<?php namespace Alcodo\CssAsync;

use Alcodo\CssAsync\Html\CssAsync;
use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->app->singleton('CssAsync', function () {
            return new CssAsync();
        });
    }

    public function boot()
    {
    }
}