<?php

namespace Alcodo\AsyncCss;

use Alcodo\AsyncCss\Html\AsyncCss;
use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->app->singleton('AsyncCss', function () {
            return new AsyncCss();
        });
    }

    public function boot()
    {
    }
}
