<?php

namespace app\modules\apiv1;

/**
 * apiv1 module definition class
 */
class ApiV1Module extends \yii\base\Module
{
    public $healthchecks;
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\apiv1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Authorization, Content-Type");
        header("Access-Control-Request-Method: POST, PUT, GET, PATCH, DELETE, OPTIONS, HEAD");
        header("Access-Control-Expose-Headers: X-Pagination-Current-Page, X-Pagination-Page-Count, X-Pagination-Per-Page, X-Pagination-Total-Count, X-Rate-Limit-Limit, X-Rate-Limit-Remaining, X-Rate-Limit-Reset");
        header("Accept-Encoding: gzip,compress");
       
        \Yii::$app->user->enableSession = false;
        \Yii::$app->user->loginUrl  = null;
        \Yii::$app->user->identityClass = 'app\models\Users';

        // custom initialization code goes here
    }
 
}
