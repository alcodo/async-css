<?php namespace Alcodo\AsyncCss\Commands;

use Alcodo\AsyncCss\Cache\CssKeys;
use Illuminate\Console\Command;

class Clear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alcodo:asynccss:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all generated css critical cache (just for dev recommended)';

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
        $result = CssKeys::removeAll();
        if ($result) {
            $this->info('Cache is now empty');
        } else {
            $this->error('Cache is not cleared!');
        }

    }
}
