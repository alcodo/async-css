# Async Css

[![License](https://poser.pugx.org/alcodo/async-css/license)](https://packagist.org/packages/approached/async-css)
[![Latest Stable Version](https://poser.pugx.org/alcodo/async-css/v/stable)](https://packagist.org/packages/approached/async-css)
[![Total Downloads](https://poser.pugx.org/alcodo/async-css/downloads)](https://packagist.org/packages/approached/async-css)

Laravel package to create a dynamic critical css

Easy workflow:
![](http://i.imgur.com/67Y5fPM.png)

# Installation

Require this package with composer:
```
composer require alcodo/async-css
```

Add ServiceProvider in config/app.php
```
Alcodo\AsyncCss\ServiceProvider::class,
    and
'AsyncCss' => Alcodo\AsyncCss\Html\Facade::class,
```

Add Middleware
```
protected $middleware = [
    \Alcodo\AsyncCss\Middleware\AsyncCssMiddleware::class,
];
```

Add Commands
```
protected $commands = [
    \Alcodo\AsyncCss\Commands\Clear::class,
    \Alcodo\AsyncCss\Commands\Show::class,
    \Alcodo\AsyncCss\Commands\Rebuild::class,
];
```


Add app.balde.php
```
{!! asyncsss(elixir('style.css')) !!}
```

## License
MIT
