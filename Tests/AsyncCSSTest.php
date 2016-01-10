<?php namespace Alcodo\AsyncCss\Tests;

use Alcodo\AsyncCss\Cache\CssKeys;
use Alcodo\AsyncCss\Jobs\BuildAsyncCSS;
use Illuminate\Support\Facades\Cache;
use TestCase;

class AsyncCSSTest extends TestCase
{
    public function testGenerateAsyncCSS()
    {
        $html = file_get_contents(__DIR__ . '/resources/index.html');
        $urlPath = '/example';

        // create
        $job = new BuildAsyncCSS($html, $urlPath);
        $job->handle();

        // check
        $cssOutput = Cache::get(CssKeys::getSingleKey($urlPath));
        $this->assertFalse(empty($cssOutput), 'Css output is empty');

        $start = '@charset "UTF-8"';
        $this->assertStringStartsWith($start, $cssOutput, 'Css output maybe wrong');
    }
}