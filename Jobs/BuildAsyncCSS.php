<?php namespace Alcodo\AsyncCss\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class BuildAsyncCSS extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $cacheKey;
    protected $urlPath;
    protected $cssfile;
    protected $servicePath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cacheKey, $urlPath, $cssfile)
    {
        $this->cacheKey = $cacheKey;
        $this->urlPath = $urlPath;
        $this->cssfile = $cssfile;
        $this->servicePath = base_path('node_modules/critical/cli.js');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tmpHtmlFile = $this->getHtml();
        if (empty($tmpHtmlFile)) {
            Log::warning('CssAync: No html file is loaded');
        }

        $cssOutput = $this->getCssOutput($tmpHtmlFile);
        if (empty($cssOutput)) {
            Log::warning('CssAync: Css output is empty');
        } else {
            Cache::forever($this->cacheKey, $cssOutput);
        }

        unlink($tmpHtmlFile);
    }

    private function getHtml()
    {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "abovefold: true"
            )
        );

        $context = stream_context_create($opts);
        Log::debug('CssAync url: ' . $this->urlPath);

        try {
            $output = file_get_contents(Request::root() . $this->urlPath, false, $context);
        } catch (\Exception $e) {
            Log::error('CssAync get content: ' . $e->getMessage());
        }

        $temp_file = sys_get_temp_dir() . '/php_css-async_' . time() . '.html';
        file_put_contents($temp_file, $output);

        return $temp_file;
    }

    private function getCssOutput($tmpHtmlFile)
    {
        if ($this->urlPath[0] === '/') {
            $this->cssfile = substr($this->cssfile, 1);
        }

        $cmd = $this->servicePath . ' ' . $tmpHtmlFile . ' -mc ' . $this->cssfile;
        Log::debug('CssAync Exec: ' . $cmd);

        return shell_exec($cmd);
    }

}
