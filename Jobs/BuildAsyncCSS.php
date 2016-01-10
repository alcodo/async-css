<?php namespace Alcodo\AsyncCss\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BuildAsyncCSS extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $cacheKey;
    protected $urlPath;
    protected $cssfile;
    protected $servicePath;
    protected $html;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($html, $urlPath)
    {
        $this->cacheKey = 'AsyncCss:' . $urlPath;
        $this->servicePath = base_path('node_modules/critical/cli.js');
        $this->html = $html;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setCssfile();

        $tmpHtmlFile = $this->setHtml();
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

    private function setHtml()
    {

        $temp_file = sys_get_temp_dir() . '/php_css-async_' . time() . '.html';
        file_put_contents($temp_file, $this->html);

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

    private function setCssfile()
    {
        preg_match_all("'<link rel=\"stylesheet\" href=\"(.*?)\">'si", $this->html, $files);
        if(isset($files[1]) && !empty($files[1])){
            $allCssFiles = $files[1];
            $this->cssfile = $allCssFiles[0];
        }
    }

}
