<?php

namespace frontend\modules\webservices\controllers;

use frontend\components\RestController;
/**
 * Default controller for the `expedia` module
 */
class DefaultController extends RestController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = '/main';
        \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
       return $this->render('index');
    }
}
