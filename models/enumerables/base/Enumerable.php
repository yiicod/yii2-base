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
     * @param $value
     * @return mixed
     */
    abstract public static function get($value);

    /**
     * Get list of the enumerable
     *
     * @static
     * @return array
     */
    abstract public static function listData();
}