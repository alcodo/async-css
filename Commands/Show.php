<?php

namespace Alcodo\AsyncCss\Commands;

use Alcodo\AsyncCss\Cache\CssKeys;
use Illuminate\Console\Command;

class Show extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alcodo:asynccss:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all generated css async paths';

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
            $this->info('Cache is empty');

            return true;
        }

        if (is_array($keys)) {
            foreach ($keys as $key) {
                $path = CssKeys::getSinglePath($key);
                $this->info('Path: '.$path);
            }
        }

        return true;
    }
}
