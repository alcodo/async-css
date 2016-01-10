# laravel-css-async
Creates a critical css with css handling

## Middleware
    protected $middleware = [
        \Alcodo\AsyncCss\Middleware\AsyncCssMiddleware::class,
    ];

## Commands
    protected $commands = [
        \Alcodo\AsyncCss\Commands\Clear::class,
        \Alcodo\AsyncCss\Commands\Show::class,
        \Alcodo\AsyncCss\Commands\Rebuild::class,
    ];

## app.php
    Alcodo\AsyncCss\ServiceProvider::class,
    'AsyncCss' => Alcodo\AsyncCss\Html\Facade::class,