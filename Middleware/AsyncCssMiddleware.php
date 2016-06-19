<?php

namespace Alcodo\AsyncCss\Middleware;

use Alcodo\AsyncCss\Cache\CssKeys;
use Alcodo\AsyncCss\Html\AsyncCss;
use Alcodo\AsyncCss\Jobs\BuildAsyncCSS;
use Closure;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Cache;

class AsyncCssMiddleware
{
    use DispatchesJobs;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->getMethod() !== 'GET') {
            return $next($request);
        }

        $response = $next($request);

        if ($this->isHtmlResponse($response)) {
            $urlPath = $request->getRequestUri();

//            var_dump('arsch'.$urlPath);

            $cacheKey = CssKeys::getSingleKey($urlPath);
////            var_dump($cacheKey);
////            dd(Cache::has($cacheKey));
            if (Cache::has($cacheKey) === false) {
                // start a asynccss job in queues mode
                $html = $response->getContent();
                $this->dispatch(new BuildAsyncCSS($html, $urlPath));
            }
        }

        return $response;
    }

    private function isHtmlResponse($response)
    {
        if (is_object($response) &&
            $response instanceof Response &&
            str_contains($response->headers->get('Content-Type'), 'text/html')
        ) {
            return false;
        }

        return true;
    }
}
