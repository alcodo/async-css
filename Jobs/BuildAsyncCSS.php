<?php

namespace Alcodo\AsyncCss\Jobs;

use Alcodo\AsyncCss\Cache\CssKeys;
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
        $this->cacheKey = CssKeys::getSingleKey($urlPath);
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
        $result = $this->setCssfile();
        if ($result === false) {
            return false;
        }

        $tmpHtmlFile = $this->setHtml();
        if (empty($tmpHtmlFile)) {
            Log::warning('AsyncCss: No html file is loaded');
        }

        $cssOutput = $this->getCssOutput($tmpHtmlFile);
        if (empty($cssOutput)) {
            Log::warning('AsyncCss: Css output is empty');
        } else {
            Cache::forever($this->cacheKey, $cssOutput);
            CssKeys::add($this->cacheKey);
        }

        unlink($tmpHtmlFile);
    }

    private function setHtml()
    {
        $temp_file = sys_get_temp_dir().'/php_async-css_'.time().'.html';
        file_put_contents($temp_file, $this->html);

        return $temp_file;
    }

    private function getCssOutput($tmpHtmlFile)
    {
        if ($this->cssfile[0] === '/') {
            $this->cssfile = substr($this->cssfile, 1);
        }

        $cmd = $this->servicePath.' '.$tmpHtmlFile.' -mc '.$this->cssfile;
        Log::debug('AsyncCss Exec: '.$cmd);

        return shell_exec($cmd);
    }

    private function setCssfile()
    {
        preg_match_all("'<link rel=\"stylesheet\" href=\"(.*?)\">'si", $this->html, $files);
        if (isset($files[1]) && ! empty($files[1])) {
            $allCssFiles = $files[1];

            if (empty($allCssFiles[0])) {
                Log::error('AsyncCss: Css file can not be parsed - Cachekey:'.$this->cacheKey);

                return false;
            }

            $this->cssfile = $allCssFiles[0];

            return true;
        }
    }
}
