<?php
/**
 * Created by PhpStorm.
 * User: theta-php
 * Date: 28/9/16
 * Time: 7:09 PM
 */
namespace console\controllers;

use common\models\Reservations;
use common\models\Details;
use yii\console\Controller;
use Yii;

class CheckInReminderController extends Controller
{
    public function actionTomorrow()
    {
    	$date = date('Y-m-d', time()+86400);    	
    	$reservation = Reservations::find()->where(['check_in_date'=>$date])->all();
    	foreach ($reservation as $reservation) {
    		$user = $reservation->creator;
    		$client = $reservation->client;    		
    		$message = 'hi '.$user->first_name.'this is to remind you that you have chek in tommorow';
    		$reservationData = Details::reservationInfo($reservation);
    		//Yii::$app->Notify->SendPush($user,DAY_BEFORE_CHECK_IN,$message,$reservationData);

    		$descriptionCheckReq = array('user_name'=>$user->username,'arrival_time'=>$reservation->arrival_time,'check_in_date'=>$reservation->check_in_date,'single_room'=>$reservation->single_rooms,'double_room'=>$reservation->double_rooms,'reservation_no'=>$reservation->reservation_no);
    		Yii::$app->Email->send(CHECK_IN_REMINDER_MAIL,$user->email,ADMIN_EMAIL,$descriptionCheckReq);

    		$descriptionCheckClient = array('user_name'=>$client->name,'arrival_time'=>$reservation->arrival_time,'check_in_date'=>$reservation->check_in_date,'single_room'=>$reservation->single_rooms,'double_room'=>$reservation->double_rooms,'reservation_no'=>$reservation->reservation_no);
    		Yii::$app->Email->send(CHECK_IN_REMINDER_MAIL,$client->email,ADMIN_EMAIL,$descriptionCheckClient);

    	}
    }

    public function actionToday()
    {
    	$date = date('Y-m-d', time());    	
    	$reservation = Reservations::find()->where(['check_in_date'=>$date])->all();
    	foreach ($reservation as $reservation) {
    		$user = $reservation->creator;
    		$client = $reservation->client;  
    		$reservation->checkin_status = CHECK_IN_ENABLE;  
    		$reservation->save();

    		$message = 'hi '.$user->first_name.'you have chek in today';
    		$reservationData = Details::reservationInfo($reservation);
    		//Yii::$app->Notify->SendPush($user,DAY_BEFORE_CHECK_IN,$message,$reservationData);

    		$descriptionCheckReq = array('user_name'=>$user->username,'arrival_time'=>$reservation->arrival_time,'check_in_date'=>$reservation->check_in_date,'single_room'=>$reservation->single_rooms,'double_room'=>$reservation->double_rooms,'reservation_no'=>$reservation->reservation_no);
    		Yii::$app->Email->send(CHECK_IN_REMINDER_MAIL,$user->email,ADMIN_EMAIL,$descriptionCheckReq);

    		$descriptionCheckClient = array('user_name'=>$client->name,'arrival_time'=>$reservation->arrival_time,'check_in_date'=>$reservation->check_in_date,'single_room'=>$reservation->single_rooms,'double_room'=>$reservation->double_rooms,'reservation_no'=>$reservation->reservation_no);
    		Yii::$app->Email->send(CHECK_IN_REMINDER_MAIL,$client->email,ADMIN_EMAIL,$descriptionCheckClient);

    	}
    }
  
}
