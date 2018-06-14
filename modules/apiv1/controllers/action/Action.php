<?php

namespace app\modules\apiv1\controllers\action;

use app\modules\apiv1\helpers\Uuid;

class Action extends \yii\rest\Action
{

    /**
     * @inheritdoc
     */
    public function findModel($id)
    {
        $id = Uuid::str2uuid($id);
        return parent::findModel($id);
    }
}