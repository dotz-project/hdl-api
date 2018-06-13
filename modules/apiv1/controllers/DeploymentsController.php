<?php

namespace app\modules\apiv1\controllers;

use yii;
use app\modules\apiv1\models\Users;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use \Firebase\JWT\JWT;

use yii\filters\auth\HttpBearerAuth;

use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

/**
 * Default controller for the `apiv1` module
 */
class DeploymentsController extends ActiveController
{
    public $modelClass = 'app\models\Deployments';
   
    public function behaviors() {
        
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['options'],
        ];

        $behaviors['rateLimiter'] = [
            'class' => \highweb\ratelimiter\RateLimiter::className(),
            'rateLimit' => 200,
            'timePeriod' => 1,
            'separateRates' => false,
            'enableRateLimitHeaders' => true,
        ];

        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = []){
        if (!Yii::$app->user->can("api.deployments.{$action}") && !empty(yii::$app->user->id)) 
            throw new \yii\web\ForbiddenHttpException(sprintf('Você não tem permissão para acessar este recurso.', $action));
        return true;
    }

    public function beforeAction($action){
        //var_dump($action);
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result){
        $result = parent::afterAction($action, $result);
        //Yii::$app->request->bodyParams
        return $result;
    }
}