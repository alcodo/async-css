<?php namespace Alcodo\AsyncCss\Middleware;

use Alcodo\AsyncCss\Jobs\BuildAsyncCSS;
use Closure;
use Illuminate\Foundation\Bus\DispatchesJobs;

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
            // start a asynccss job in queues mode
            $html = $response->getContent();
            $urlPath = $request->getRequestUri();
            $this->dispatch(new BuildAsyncCSS($html, $urlPath));
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
