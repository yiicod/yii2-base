<?php

namespace yiicod\base\helpers;

use Throwable;

/**
 * Class LoggerMessage
 * Logger helper
 *
 * @package yiicod\base\helpers
 */
class LoggerMessage
{
    /**
     * Return error message
     *
     * @param Throwable $e
     * @param string $additional
     *
     * @return string
     */
    public static function log(Throwable $e, string $additional = ''): string
    {
        if (false === empty($additional)) {
            return sprintf("%s (%s : %s)\nAdditional message:\n%s\nStack trace:\n%s", $e->getMessage(), $e->getFile(), $e->getLine(), $additional, $e->getTraceAsString());
        } else {
            return sprintf("%s (%s : %s)\nStack trace:\n%s", $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
        }
    }

    /**
     * Get trace message
     *
     * @param string $message
     * @param array $data
     *
     * @return string
     */
    public static function trace(string $message, array $data = []): string
    {
        return self::message($message, $data, 5);
    }

    /**
     * Get info message
     *
     * @param string $message
     * @param array $data
     *
     * @return string
     */
    public static function info(string $message, array $data = []): string
    {
        if (YII_ENV_TEST) {
            return self::message($message, $data, 5);
        } else {
            return '';
        }
    }

    /**
     * Get message with trace level
     *
     * @param string $message
     * @param array $data
     * @param int $traceLevel
     *
     * @return string
     */
    private static function message(string $message, array $data = [], $traceLevel = 5): string
    {
        $msg = sprintf('%s', str_replace(array_map(function ($item) {
            return '{' . $item . '}';
        }, array_keys($data)),
            array_values($data), $message));
        $msg = sprintf('%s', str_replace(array_keys($data), array_values($data), $msg));
        if ($traceLevel > 0) {
            $traces = debug_backtrace();
            unset($traces[0]); //Remove LoggerMessage from trace
            $count = 0;
            foreach ($traces as $trace) {
                if (isset($trace['file'], $trace['line']) && strpos($trace['file'], YII2_PATH) !== 0) {
                    $msg .= "\nin " . $trace['file'] . ' (' . $trace['line'] . ')';
                    if (++$count >= $traceLevel) {
                        break;
                    }
                }
            }
        }

        return $msg;
    }
}
