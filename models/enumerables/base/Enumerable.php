<?php

namespace yiicod\base\models\enumerables\base;

/**
 * Enumerable is the base class for all enumerable types.
 *
 * To define an enumerable type, extend Enumberable and define string constants.
 * Each constant represents an enumerable value.
 * The constant name must be the same as the constant value.
 * For example,
 * <pre>
 * class TextAlign extends Enumerable
 * {
 *     const Left='Left';
 *     const Right='Right';
 * }
 * </pre>
 * Then, one can use the enumerable values such as TextAlign::Left and
 * TextAlign::Right.
 *
 * @author Alexey Orlov <aaorlo88@gmail.com>
 */
abstract class Enumerable
{
    /**
     * Get label of the enumerable
     *
     * @static
     *
     * @param $value
     *
     * @return mixed
     */
    public static function get($value)
    {
        $list = static::data();

        return isset($list[$value]) ? $list[$value] : $value;
    }

    /**
     * Get list of the enumerable
     *
     * @static
     *
     * @param array $exclude
     *
     * @return array
     */
    public static function listData(array $exclude = []): array
    {
        $list = static::data();
        foreach ($exclude as $item) {
            unset($list[$item]);
        }

        return $list;
    }

    /**
     * Check if value in into keys
     *
     * @param $value
     *
     * @return bool
     */
    public static function inKeys($value): bool
    {
        return in_array($value, array_keys(static::listData([])));
    }

    /**
     * Check if value in into values
     *
     * @param $value
     *
     * @return bool
     */
    public static function inValues($value): bool
    {
        return in_array($value, array_keys(static::listData([])));
    }

    /**
     * Get list of the enumerable
     *
     * @static
     *
     * @return array
     */
    abstract protected static function data(): array;
}
