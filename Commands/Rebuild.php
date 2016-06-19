<?php

namespace Alcodo\AsyncCss\Commands;

use Alcodo\AsyncCss\Cache\CssKeys;
use Illuminate\Console\Command;

class Rebuild extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alcodo:asynccss:rebuild';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild complete all css pages';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $keys = CssKeys::show();

        if ($keys === null) {
            $this->warn('Cache is empty');

            return true;
        }

        if (is_array($keys)) {
            foreach ($keys as $key) {
                $path = CssKeys::getSinglePath($key);

                // TODO call the path
                // $this->call($path);

                $this->info('Path: '.$path);
            }
        }
    }

    /**
     * Call the given URI and return a Response.
     *
     * @param  string $uri
     *
     * @return \Illuminate\Http\Response
     */
    protected function call($uri)
    {
        $request = Request::create($uri, 'GET');
        $kernel = $this->app->make(HttpKernel::class);
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);

        return $response;
    }
}
