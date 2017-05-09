<?php
namespace frontend\controllers;

use frontend\components\CustController;
use Yii;
use yii\web\Controller;


/**
 * Site controller
 */
class ClientController extends CustController
{

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionChooseServices()
    {

        return $this->render('index');
    }

}
