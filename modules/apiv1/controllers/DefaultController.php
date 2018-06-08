<?php

namespace app\modules\apiv1\controllers;
use yii\web\Controller;

/**
 * Default controller for the `apiv1` module
 */
class DefaultController extends Controller
{
    public $layout = false;
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
