<?php

namespace yiicod\base\models\behaviors;

use yii\base\Behavior;
use yii\base\InvalidParamException;

class AttributesMapBehavior extends Behavior
{
    /**
     * Mapping
     *
     * @var array
     */
    public $attributesMap = [];

    /**
     * Returns a value indicating whether the model has an attribute with the specified name.
     *
     * @param string $name the name of the attribute
     *
     * @return bool whether the model has an attribute with the specified name
     */
    public function hasAttr($name)
    {
        $fieldAttr = $this->prepareField($name);

        return isset($this->attributesMap[$fieldAttr]) &&
        $this->owner->hasAttribute($this->attributesMap[$fieldAttr]);
    }

    /**
     * Returns the named attribute value.
     * If this record is the result of a query and the attribute is not loaded,
     * null will be returned.
     *
     * @param string $name the attribute name
     *
     * @return mixed the attribute value. Null if the attribute is not set or does not exist.
     *
     * @see hasAttribute()
     */
    public function getAttr($name)
    {
        $fieldAttr = $this->prepareField($name);

        return $this->owner->getAttribute($this->attributesMap[$fieldAttr]);
    }

    /**
     * Sets the named attribute value.
     *
     * @param string $name the attribute name
     * @param mixed $value the attribute value
     *
     * @throws InvalidParamException if the named attribute does not exist
     *
     * @see hasAttribute()
     */
    public function setAttr($name, $value)
    {
        if ($this->hasAttr($name)) {
            $fieldAttr = $this->prepareField($name);
            $this->owner->{$this->attributesMap[$fieldAttr]} = $value;
        } else {
            throw new InvalidParamException(get_class($this) . ' has no attribute named "' . $name . '".');
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if ($this->hasAttr($name)) {
            $this->setAttr($name, $value);
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (0 === strpos($name, 'field') && $this->hasFieldByModelMap($name)) {
            return $this->getFieldByModelMap($name);
        }
        if ($this->hasAttr($name)) {
            return $this->getAttr($name);
        } else {
            return parent::__get($name);
        }
    }

    /**
     * @param type $name
     *
     * @return bool
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if (0 === strpos($name, 'field') && $this->hasFieldByModelMap($name)) {
            return true;
        }

        if ($this->hasAttr($name)) {
            return true;
        } else {
            return parent::canGetProperty($name, $checkVars = true);
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if ($this->hasAttr($name)) {
            return true;
        } else {
            return parent::canSetProperty($name, $checkVars = true);
        }
    }

    /**
     * @param string $name
     *
     * @return type
     */
    public function getFieldByModelMap($name)
    {
        if ($this->hasFieldByModelMap($name)) {
            return $this->attributesMap[$name];
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasFieldByModelMap($name)
    {
        return isset($this->attributesMap[$name]);
    }

    /**
     * Prepare dyn field
     *
     * @param $name
     *
     * @return string
     */
    protected function prepareField($name)
    {
        $parts = explode('_', $name);
        $fieldAttr = 'field';
        foreach ($parts as $part) {
            $fieldAttr .= ucfirst($part);
        }

        return $fieldAttr;
    }
}
