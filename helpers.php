<?php

if (!function_exists('asyncsss')) {
    /**
     * Generate a international date string
     *
     * @param $type
     * @param $dateObject
     * @return string
     */
    function asyncsss($cssfiles)
    {
        return app('AsyncCss')->getHtmlCss($cssfiles);
    }
}