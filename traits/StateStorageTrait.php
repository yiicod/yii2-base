<?php

namespace yiicod\base\traits;

use Yii;
use yii\helpers\ArrayHelper;
use yii\mongodb\Collection;
use yii\mongodb\Query;

/**
 * Class StateStorageTrait
 * Trait to work with file state storage as GlobalState in Yii 1
 *
 * Just attach this trait to file and override getStorageFileName() static trait method
 * to provide new storage file name. str_replace('\\', '', self::class).'.bin' will be used by default.
 * Or override getStorageFilePath() method to change file folder destination.
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package yiicod\base\traits
 */
trait StateStorageTrait
{
    /**
     * Get storage state value
     *
     * @param string $key
     * @param $default
     *
     * @return mixed
     */
    public static function getStorageState(string $key, $default = null)
    {
        $data = self::getStorageData();

        return isset($data[$key]) ? $data[$key] : $default;
    }

    /**
     * Set storage state value
     *
     * @param string $key
     * @param $value
     */
    public static function setStorageState(string $key, $value)
    {
        self::setStorageData([$key => $value]);
    }

    /**
     * Get storage data
     *
     * @return array|string
     */
    public static function getStorageData()
    {
        $query = new Query();
        $row = $query
            ->from('yii_state')
            ->where(['name' => self::getStorageKey()])
            ->one();

        return $row['data'] ?? [];
    }

    /**
     * Add data to storage
     *
     * @param array $newData
     */
    public static function setStorageData(array $newData)
    {
        $data = ArrayHelper::merge(self::getStorageData(), $newData);
        /** @var Collection $collection */
        $collection = Yii::$app->mongodb->getCollection('yii_state');
        $collection->update(['name' => self::getStorageKey()], ['name' => self::getStorageKey(), 'data' => $data], [
            'upsert' => true,
        ]);
    }

    /**
     * Method which has to be implemented by classes
     * Return path of storage folder to save data in
     *
     * @return string
     */
    abstract protected static function getStorageKey(): string;
}
