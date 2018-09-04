<?php

namespace yiicod\base\traits;

use yii\db\ActiveRecordInterface;
use yii\web\HttpException;

trait FindRecordTrait
{
    /**
     * Returns the data model based on the primary key given in the param.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     * @param string $class
     *
     * @return ActiveRecordInterface the loaded model
     *
     * @throws HttpException
     */
    public function findModel($id, $class)
    {
        if ($model = $class::findOne($id)) {
            return $model;
        }
        throw new HttpException(404, 'The requested page does not exist.');
    }
}
