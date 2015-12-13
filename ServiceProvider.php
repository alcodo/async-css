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


            // Next we need to grab the engine resolver instance that will be used by the
            // environment. The resolver will be used by an environment to get each of
            // the various engine implementations such as plain PHP or Blade engine.
//            $resolver = $app['view.engine.resolver'];
//
//            $finder = $app['view.finder'];
//
//            $env = new Factory($resolver, $finder, $app['events']);
//
//            // We will also set the container instance on this view environment since the
//            // view composers may be classes registered in the container, which allows
//            // for great testable, flexible composers for the application developer.
//            $env->setContainer($app);
//
//            $env->share('app', $app);
//
//            return $env;
        });

    }

    public function boot()
    {
    }
}