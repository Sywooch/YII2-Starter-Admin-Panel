<?php
namespace backend\controllers;
use backend\components\CustController;
use common\models\Reservations;
use common\models\User;
use common\models\WebNotification;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class DashboardController extends CustController
{

    public function actionIndex()
    {
        return $this->render('index');
    }
}
