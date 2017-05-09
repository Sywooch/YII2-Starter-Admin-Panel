<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 23/9/16
 * Time: 11:06 AM
 */
namespace frontend\modules\webservices\controllers;

use common\models\Details;

use common\models\Reservations;
use Yii;
use frontend\components\RestController;
use yii\base\Exception;
use yii\helpers\Json;


class FacilityController extends RestController
{


    public function actionApproved() {
        if(isset($_GET['reservation_id'])) {

            $model = Reservations::findOne($_GET['reservation_id']);
            if($model){
                $model->status = Reservations::PENDING_CHECK_IN;
                $model->save(false);
                Yii::$app->commonfunction->changeStatusForAll($model,Reservations::PENDING_CHECK_IN);
                $code = 200;
                $data = $this->makeResponse($code,'Facility Approved',Details::reservationInfo($model));

            }else{
                $code = 400;
                $data = $this->makeResponse($code, 'something went wrong!');
            }

        }else{
            $code = 400;
            $data = $this->makeResponse($code, 'something went wrong!');
        }

        $this->sendResponse($code, JSON::encode($data));
    }
    public function actionReject() {
        if(isset($_GET['reservation_id'])) {

            $model = Reservations::findOne($_GET['reservation_id']);
            if($model){
                $model->facilities_id = NULL;
                $model->hotel_confirmed = 0;
                $model->confirmation_no = null;
                $model->status = Reservations::PROCESSING;
                $model->save(false);
                Yii::$app->commonfunction->changeStatusForAll($model,Reservations::PROCESSING);
                $code = 200;
                $data = $this->makeResponse($code,'Facility Cancelled',Details::reservationInfo($model));

            }else{
                $code = 400;
                $data = $this->makeResponse($code, 'something went wrong!');
            }

        }else{
            $code = 400;
            $data = $this->makeResponse($code, 'something went wrong!');
        }

        $this->sendResponse($code, JSON::encode($data));
    }


}