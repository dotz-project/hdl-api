<?php

namespace app\validators;

use app\modules\apiv1\helpers\Uuid;

class UuidValidator extends \yii\validators\Validator
{
    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = Uuid::str2uuid($model->$attribute);
    }

}