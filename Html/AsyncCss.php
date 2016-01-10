<?php namespace Alcodo\AsyncCss\Html;

use Alcodo\AsyncCss\Jobs\BuildAsyncCSS;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AsyncCss
{

    use DispatchesJobs;

    public function __construct()
    {
    }

    public function getHtmlCss($cssfiles)
    {
        $head = Request::header();
        if (isset($head['abovefold'])) {
            return null;
        }

        $files = is_array($cssfiles) ? $cssfiles : func_get_args();
        $cacheKey = 'css_' . Request::path();

//        if (Cache::has($cacheKey)) {
//            $cssoutput = Cache::get($cacheKey);
//            return $this->getAsyncStylesheet($cssoutput, $files);
//        } else {

            // create async css in queues
//            $urlPath = '/' . Request::path();
//            $this->dispatch(new BuildAsyncCSS($cacheKey, $urlPath, $files[0]));

            return $this->getStylesheetLink($files);
//        }
    }

    protected function getStylesheetLink($cssfiles)
    {
        $output = '';
        foreach ($cssfiles as $file) {
            $output .= "<link rel=\"stylesheet\" href=\"$file\">";
        }
        return $output;
    }

    private function getAsyncStylesheet($cssoutput, $cssfiles)
    {
        // async css
        $output = "<style>$cssoutput</style>";

        // load css
        $output .= "<script defer>";
        $output .= $this->getLoadCSSScript();
        foreach ($cssfiles as $file) {
            $output .= "loadCSS(\"$file\");";
        }
        $output .= "</script>";

        return $output;
    }

    private function getLoadCSSScript()
    {
        /*!
        loadCSS: load a CSS file asynchronously.
        [c]2015 @scottjehl, Filament Group, Inc.
        Licensed MIT

        https://github.com/filamentgroup/loadCSS/blob/master/loadCSS.js
        */
        return '!function(e){"use strict";var n=function(n,t,o){var l,r=e.document,i=r.createElement("link");if(t)l=t;else{var a=(r.body||r.getElementsByTagName("head")[0]).childNodes;l=a[a.length-1]}var d=r.styleSheets;i.rel="stylesheet",i.href=n,i.media="only x",l.parentNode.insertBefore(i,t?l:l.nextSibling);var f=function(e){for(var n=i.href,t=d.length;t--;)if(d[t].href===n)return e();setTimeout(function(){f(e)})};return i.onloadcssdefined=f,f(function(){i.media=o||"all"}),i};"undefined"!=typeof module?module.exports=n:e.loadCSS=n}("undefined"!=typeof global?global:this);';
    }

}