<?php
namespace frontend\controllers;


use common\models\Reservations;
use common\models\search\ReservatationSearch;
use frontend\components\CustController;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class ReservationsController extends CustController
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReservatationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = PAGESIZE;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * View Details page of Reservation.
     */
    public function actionView($id)
    {
        $model = Reservations::find()->where(['id' => $id])->one();
        return $this->render('viewdetails', ['model' => $model]);
    }





}
