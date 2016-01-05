<?php namespace Alcodo\AsyncCss\Tests;

use Alcodo\CssAsync\Jobs\BuildAsyncCSS;
use Illuminate\Support\Facades\Cache;
use TestCase;

class AsyncCSSTest extends TestCase
{
    public function testGenerateAsyncCSS()
    {

        $urlPath = '/';
        $cacheKey = 'css_' . $urlPath;
        $cssfile = '/public/style.css';

        $job = new BuildAsyncCSS($cacheKey, $urlPath, $cssfile);
        $job->handle();

        $cssOutput = Cache::get($cacheKey);
        $this->assertFalse(empty($cssOutput), 'Css output is empty');

        $start = '@charset "UTF-8"';
        $this->assertStringStartsWith($start, $cssOutput, 'Css output maybe wrong');
    }
}