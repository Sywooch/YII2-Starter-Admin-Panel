<?php
namespace backend\components;

use common\models\Reservations;
use yii\base\Widget;
use yii\web\NotFoundHttpException;


class StatusWidget extends Widget
{
    public $reservation;

    public function init()
    {
        parent::init();
        if ($this->reservation === null) {
            throw new NotFoundHttpException();
        }
    }

    public function run()
    {
        $model = $this->reservation;
        if ($model->status == Reservations::PROCESSING) {
            return '<button type="button" ajax-url="'.\Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status').'" reservation="'.$model->id.'" class="btn btn-warning btn-xs status-btn">in-progress</button>';
        } elseif ($model->status == Reservations::WAITING && $model->hotel_confirmed == ACTIVE) {
            return '<button type="button" ajax-url="'.\Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status').'" reservation="'.$model->id.'" class="btn btn-default btn-xs status-btn">Waiting on <br>Client confirmation</button>';
        } elseif ($model->status == Reservations::WAITING && $model->hotel_confirmed == INACTIVE) {
            return '<button type="button" ajax-url="'.\Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status').'" reservation="'.$model->id.'" class="btn btn-default btn-xs status-btn">Waiting on <br>Facility confirmation</button>';
        } elseif ($model->status == Reservations::CANCEL_BY_CLIENT) {
            return '<button type="button" ajax-url="'.\Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status').'" reservation="'.$model->id.'" class="btn btn-danger btn-xs status-btn">Cancelled By client</button>';
        } elseif ($model->status == Reservations::CANCEL_BY_CREWFACTS) {
            return '<button type="button" ajax-url="'.\Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status').'" reservation="'.$model->id.'" class="btn btn-danger btn-xs status-btn">Cancelled By crewfacts</button>';
        } elseif ($model->status == Reservations::PENDING_CHECK_IN && $model->check_in_date == date('Y-m-d')) {
            return '<button type="button" ajax-url="'.\Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status').'" reservation="'.$model->id.'" class="btn btn-primary btn-xs status-btn">Check in Tonight</button>';
        } elseif ($model->status == Reservations::PENDING_CHECK_IN) {
            return '<button type="button" ajax-url="'.\Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status').'" reservation="'.$model->id.'" class="btn btn-primary btn-xs status-btn">Future Check in</button>';
        } elseif ($model->status == Reservations::CHECKED_IN && $model->check_out_date == date('Y-m-d')) {
            return '<button type="button" ajax-url="'.\Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status').'" reservation="'.$model->id.'" class="btn btn-success btn-xs status-btn">Checking Out Today</button>';
        } elseif ($model->status == Reservations::CHECKED_IN) {
            return '<button type="button" ajax-url="'.\Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status').'" reservation="'.$model->id.'" class="btn btn-success btn-xs status-btn">Checked in</button>';
        } elseif ($model->status == Reservations::EXTEND) {
            return '<button type="button" ajax-url="'.\Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status').'" reservation="'.$model->id.'" class="btn btn-warning btn-xs status-btn">Extend request</button>';
        } elseif ($model->status == Reservations::CHECK_OUT) {
            return '<button type="button" ajax-url="'.\Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status').'" reservation="'.$model->id.'" class="btn btn-success btn-xs status-btn">Checked Out</button>';
        }
    }
}

?>
