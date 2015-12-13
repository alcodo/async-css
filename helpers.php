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
        var_dump(34);
        return app('cssasync')->getHtmlCss($cssfiles);
    }
}