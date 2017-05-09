<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 23/9/16
 * Time: 11:06 AM
 */
namespace frontend\modules\webservices\controllers;

use common\models\Details;
use common\models\form\ReservationForm;
use common\models\Persons;
use common\models\queries\ReservationsQuery;
use common\models\ReaservationGuest;
use common\models\ReservationRooms;
use common\models\Reservations;
use common\models\AppNotification;

use Yii;
use frontend\components\RestController;
use yii\base\Exception;
use yii\helpers\Json;


class AppNotificationController extends RestController
{
	public function actionIndex()
    {
    	try {
            $tokendata = $this->checkAuth();            
             $result = [];
             $limit = LIMIT;
             if (isset($_GET['page'])) {
                      $offset = ($_GET['page'] - 1) * $limit;
                  } else {
                      $offset = 0;
                  }
                  $notifications = AppNotification::find()->where(['to_id'=>$tokendata->user_id])->orderBy('created_date DESC')->limit($limit)->offset($offset)->all();
                  foreach($notifications as $notification){
                        array_push($result,Details::notificationInfo($notification));
                    }
                    $code = 200;
                    $data = ['data'=>$result];
        }  catch (Exception $e) {
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data));
	}
	public function actionClear()
    {
    	try {
            $tokendata = $this->checkAuth(); 
            if(AppNotification::deleteAll(['to_id'=>$tokendata->user_id])) {
            	$code = 200;
            	$data = $this->makeResponse($code, 'cleared all notification.');
            }else{
            	$code = 400;
            	$data = $this->makeResponse($code,'error occured while trying to clear notification');
            }        
          
        }  catch (Exception $e) {
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data)); 
    }   
}