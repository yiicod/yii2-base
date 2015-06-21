<?php

namespace application\models\behaviors;

use CActiveRecordBehavior;
use yii\db\ActiveRecord;

/**
 * Class DefaultAttributesBehavior
 * For all models
 * 
 * Save default/original data from record
 *
 * @package app\models\behaviors
 */
class DefaultAttributesBehavior extends CActiveRecordBehavior
{

    /**
     * @var array List
     */
    protected $defaultAttributes = array();

    /**
     * 
     * @return Array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    /**
     * After find saves values of attributes like defaults
     *
     * @param \CEvent $event
     */
    public function afterFind($event)
    {
        parent::afterFind($event);
        $this->defaultAttributes = $this->owner->attributes;
    }

    /**
     * Gets default value of the attribute
     *
     * @param $attribute
     * @param null $default
     * @return null
     */
    public function getDefaultAttributes()
    {
        return $this->defaultAttributes;
    }

    /**
     * Gets default value of the attribute
     *
     * @param $attribute
     * @param null $default
     * @return null
     */
    public function getDefaultAttribute($attribute, $default = null)
    {
        if (isset($this->defaultAttributes[$attribute])) {
            if (null === $this->defaultAttributes[$attribute]) {
                return $default;
            }
            return $this->defaultAttributes[$attribute];
        }
        return $default;
    }

    /**
     * Checks are default attributes exist
     *
     * @return bool
     */
    public function isExistsDefaultAttributes()
    {
        return !$this->owner->isNewRecord && !empty($this->defaultAttributes);
    }

}
