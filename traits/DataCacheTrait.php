<?php

namespace yiicod\base\traits;

/**
 * Class DataCacheTrait
 * Deduplicate data resolver.
 *
 * @package yiicod\base\traits
 */
trait DataCacheTrait
{
    private static $dataCache = [];

    /**
     * @param array $values
     *
     * @return string
     */
    public static function keyDataCache($values = [])
    {
        if (is_array($values)) {
            return implode('', array_merge([__CLASS__], $values));
        }

        return __CLASS__ . $values;
    }

    /**
     * Get value by key. If value is not set then will be called callback
     *
     * @param string|array $key
     * @param callable $callback
     * @param bool $reset
     *
     * @return mixed
     */
    public static function getDataCache($key, $callback, $reset = false)
    {
        if (true === $reset) {
            self::resetDataCache($key);
        }
        $key = self::keyDataCache($key);
        if (false === array_key_exists($key, self::$dataCache) || true === $reset) {
            self::$dataCache[$key] = $callback();
        }

        return self::$dataCache[$key];
    }

    /**
     * Reset data by key
     *
     * @param string $key
     *
     * @return bool
     */
    public static function resetDataCache($key)
    {
        $key = self::keyDataCache($key);

        unset(self::$dataCache[$key]);

        return true;
    }
}
