<?php

namespace Alcodo\AsyncCss\Cache;

use Illuminate\Support\Facades\Cache;

class CssKeys
{
    const CacheKey = 'AsyncCss:';
    const MasterAllKey = 'All:AsyncCss';

    public static function getSingleKey($path)
    {
        return self::CacheKey.$path;
    }

    public static function getSinglePath($key)
    {
        return str_replace(self::CacheKey, '', $key);
    }

    public static function show()
    {
        return Cache::get(self::MasterAllKey);
    }

    public static function add($key)
    {
        $allKeys = Cache::get(self::MasterAllKey);

        if (is_null($allKeys)) {
            // create first time
            Cache::forever(self::MasterAllKey, [$key]);
        } else {
            if (! in_array($key, $allKeys)) {
                // add
                $allKeys[] = $key;
            }

            // save
            return Cache::forever(self::MasterAllKey, $allKeys);
        }
    }

    public static function remove($key)
    {
        $allKeys = Cache::get(self::MasterAllKey);

        if (is_null($allKeys)) {
            return false;
        } else {
            if (($key = array_search($key, $allKeys)) !== false) {
                // remove
                unset($allKeys[$key]);
                Cache::forget($allKeys[$key]);
            } else {
                // key not exists
                return false;
            }

            // save
            return Cache::forever(self::MasterAllKey, $allKeys);
        }
    }

    public static function removeAll()
    {
        return Cache::forget(self::MasterAllKey);
    }
}
