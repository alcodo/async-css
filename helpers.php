<?php

if (!function_exists('cssasync')) {
    /**
     * Generate a international date string
     *
     * @param $type
     * @param $dateObject
     * @return string
     */
    function cssasync($cssfiles)
    {
        return app('CssAsync')->getHtmlCss($cssfiles);
    }
}