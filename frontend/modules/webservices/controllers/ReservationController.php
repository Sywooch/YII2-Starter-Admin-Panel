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
use common\models\ReaservationGuest;
use common\models\ReservationExtendRequest;
use common\models\ReservationRooms;
use common\models\Reservations;
use common\models\RoomsData;
use common\models\WebNotification;
use frontend\components\Logger;
use Yii;
use frontend\components\RestController;
use yii\base\Exception;
use yii\helpers\Json;


class ReservationController extends RestController
{


    public function actionIndex()
    {

        $device = $this->checkAuth();
        if (Yii::$app->request->isPost) {

            $postdata = JSON::decode(file_get_contents("php://input"));
            Logger::add($postdata,"request");
            $model = new ReservationForm();
            $model->scenario = ReservationForm::REST_API;
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            if ($postdata) {

                //contact person info
                $model->contact_person_name = $postdata['lead_person_info']['first_name'] . " " . $postdata['lead_person_info']['last_name'];
                $model->contact_person_email = $postdata['lead_person_info']['email'];
                $model->contact_person_phone = $postdata['lead_person_info']['cell_number'];
                $model->po_no = $postdata['lead_person_info']['po_no'];
                $model->job_no = $postdata['lead_person_info']['job_no'];
                $model->arrival_time = date("H:i", strtotime($postdata['lead_person_info']['arrival_time']));
                //Jobsite location
                if(isset($postdata['job_site_location']['is_geo']) && $postdata['job_site_location']['is_geo'] == true){
                    $model->geo_long = $postdata['job_site_location']['loc_long'];
                    $model->geo_lat= $postdata['job_site_location']['loc_lat'];
                }else{
                    $model->is_cross_street = $postdata['job_site_location']['is_cross_street'];
                    $model->city = $postdata['job_site_location']['loc_city'];
                    $model->zip = $postdata['job_site_location']['loc_zip'];
                    $model->street = $postdata['job_site_location']['loc_street'];
                    $model->state = $postdata['job_site_location']['loc_state'];
                }

                //Job Description
                $model->check_in_date = date('Y-m-d', strtotime($postdata['contract_info']['check_in_date']));
                $model->check_out_date = date('Y-m-d', strtotime($postdata['contract_info']['check_out_date']));
                $model->total_room = $postdata['contract_info']['no_room'];
                $model->single_rooms = $postdata['contract_info']['single_room'];
                $model->double_rooms = $postdata['contract_info']['double_room'];
                if ($model->validate()) {
                    $reservation = new Reservations();
                    $reservation->scenario = ReservationForm::REST_API;
                    $reservation->attributes = $model->attributes;
                    $reservation->requested_by = $device->user_id;
                    $reservation->created_by = $device->user_id;
                    $reservation->created_date = time();
                    $reservation->client_id = $device->user->profile->client_id;
                    $reservation->staff_id = $this->autoAssign();
                    $reservation->status = Reservations::PROCESSING;
                    $reservation->reservation_no = $model->getReservationNumber();
                    $reservation->platform = ($device->device_platform=='android')?ANDROID:IOS;
                    $reservation->facility_setup = INACTIVE;
                    if ($reservation->validate()) {
                        $reservation->save();
                        $commit_flg = 0;
                            foreach ($postdata['occupant_info'] as $room_type => $room_info) {
                                if ($room_type == ReservationRooms::DOUBLE) {
                                    foreach ($room_info as $info) {
                                        $room = new ReservationRooms();
                                        $room->reservation_id = $reservation->id;
                                        $room->room_type = ReservationRooms::DOUBLEROOM;
                                        $room->check_in_date =date('Y-m-d', strtotime($postdata['contract_info']['check_in_date']));
                                        $room->check_out_date =date('Y-m-d', strtotime($postdata['contract_info']['check_out_date']));
                                        $room->status = ACTIVE;
                                        if ($room->save()) {
                                            $datearray = Yii::$app->commonfunction->createDateRangeArray($room->check_in_date,$room->check_out_date);
                                            array_pop($datearray);
                                            foreach($datearray as $date){
                                                $roomsdata = new RoomsData();
                                                $roomsdata->reservation_rooms_id = $room->id;
                                                $roomsdata->date_at_facility = $date;
                                                $roomsdata->buy_rate = NULL;
                                                $roomsdata->sell_rate = NULL;
                                                $roomsdata->save(false);
                                            }
                                            for ($i = 0; $i < ReservationRooms::DOUBLEROOM; $i++) {
                                                $person = new Persons();
                                                $person->first_name = empty($info[$i]['first_name'])?'first name':$info[$i]['first_name'];
                                                $person->last_name = empty($info[$i]['last_name'])?'last name':$info[$i]['last_name'];
                                                if ($person->save()) {
                                                    $res_guest = new ReaservationGuest();
                                                    $res_guest->reservation_room_id = $room->id;
                                                    $res_guest->person_id = $person->id;
                                                    if ($res_guest->save()) {
                                                        $commit_flg = 1;
                                                    } else {
                                                        $transaction->rollBack();
                                                        $this->errorResponder($postdata, $res_guest->getErrors());
                                                    }
                                                } else {
                                                    $transaction->rollBack();
                                                    $this->errorResponder($postdata, $person->getErrors());
                                                }
                                            }

                                        } else {
                                            $transaction->rollBack();
                                            $this->errorResponder($postdata, $room->getErrors());
                                        }
                                    }
                                } else if ($room_type == ReservationRooms::SINGLE) {
                                    foreach ($room_info as $info) {
                                        $room = new ReservationRooms();
                                        $room->reservation_id = $reservation->id;
                                        $room->room_type = ReservationRooms::SINGLEROOM;
                                        $room->check_in_date =date('Y-m-d', strtotime($postdata['contract_info']['check_in_date']));
                                        $room->check_out_date =date('Y-m-d', strtotime($postdata['contract_info']['check_out_date']));
                                        $room->status = ACTIVE;
                                        if ($room->save()) {
                                            $datearray = Yii::$app->commonfunction->createDateRangeArray($room->check_in_date,$room->check_out_date);
                                            array_pop($datearray);
                                            foreach($datearray as $date){
                                                $roomsdata = new RoomsData();
                                                $roomsdata->reservation_rooms_id = $room->id;
                                                $roomsdata->date_at_facility = $date;
                                                $roomsdata->buy_rate = NULL;
                                                $roomsdata->sell_rate = NULL;
                                                $roomsdata->save(false);
                                            }
                                            for ($i = 0; $i < ReservationRooms::SINGLEROOM; $i++) {
                                                $person = new Persons();
                                                $person->first_name = empty($info[$i]['first_name'])?'first name':$info[$i]['first_name'];
                                                $person->last_name = empty($info[$i]['last_name'])?'last name':$info[$i]['last_name'];
                                                if ($person->save()) {
                                                    $res_guest = new ReaservationGuest();
                                                    $res_guest->reservation_room_id = $room->id;
                                                    $res_guest->person_id = $person->id;
                                                    if ($res_guest->save()) {
                                                        $commit_flg = 1;
                                                    } else {
                                                        $transaction->rollBack();
                                                        $this->errorResponder($postdata, $res_guest->getErrors());
                                                    }
                                                } else {
                                                    $transaction->rollBack();
                                                    $this->errorResponder($postdata, $person->getErrors());
                                                }
                                            }

                                        } else {
                                            $transaction->rollBack();
                                            $this->errorResponder($postdata, $room->getErrors());
                                        }
                                    }
                                }
                            }
                    } else {
                        $transaction->rollBack();
                        $this->errorResponder($postdata, $reservation->getErrors());
                    }

                } else {
                    $transaction->rollBack();
                    $this->errorResponder($postdata, $model->getErrors());
                }


                if ($commit_flg == 1) {
                    $transaction->commit();
                    WebNotification::add($reservation->id,1,$reservation->client_id,$reservation->staff_id);
                    $code = $data['code'] = 201;
                    $data['message'] = $this->getStatusCodeMessage($code);
                    $data['description'] = "Reservation requested successfully";
                }

            } else {
                $code = $data['code'] = 500;
                $data['message'] = $this->getStatusCodeMessage($code);
            }


        }
        Logger::add($data,"request");
        $this->sendResponse($code, JSON::encode($data));
    } 
   
    protected function autoAssign(){
            $sql = "SELECT count(reservations.id) as count,reservations.staff_id, user.role FROM reservations INNER JOIN user ON reservations.staff_id=user.id where user.is_available=1 AND user.role IN (1,3,2) GROUP BY reservations.staff_id";
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($sql);
            $staff = $command->queryAll();
            $reservationist =[];
            $admin = [];
            $superAdmin = [];

            if($staff){
            foreach ($staff as $value) {
               if($value['role'] ==  RESERVATIONIST){
                        //array_push($reservationist,$value['count']);
                   $reservationist =  Reservations::array_push_assoc($reservationist,$value['staff_id'],$value['count']);
                }elseif($value['role'] ==  ADMIN){
                   $admin =  Reservations::array_push_assoc($admin,$value['staff_id'],$value['count']);
                }
            }
              /*  print_r($reservationist);
                print_r($admin);*/
            asort($reservationist);
            asort($admin);

           /*     print_r($reservationist);
                print_r($admin);*/
             //reservationist
            if(current($reservationist) ==  end($reservationist)){
                //admin check
                return key($admin);
            }else{
                return key($reservationist);
            }
        }else{
           $data =NULL;
        }
         
    }
    /*protected function autoAssign(){
            $sql = "SELECT count(reservations.id) as count,reservations.staff_id FROM reservations INNER JOIN user ON reservations.staff_id=user.id where user.is_available=1 AND user.role IN (1,3,2) GROUP BY reservations.staff_id";
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($sql);
            $staff = $command->queryAll();
            $reservationist =[];
            $admin = [];
            $superAdmin = [];
            if($staff){
            foreach ($staff as $value) {
               if(Yii::$app->commonfunction->isReservationist($value['staff_id'])){
                        array_push($reservationist,$value);
                }elseif(Yii::$app->commonfunction->isAdmin($value['staff_id'])){
                        array_push($admin, $value);
                }elseif(Yii::$app->commonfunction->isSuperAdmin($value['staff_id'])){
                        array_push($superAdmin, $value);
                }
            }
            if(isset($reservationist) && !empty($reservationist)){
                $min = min(array_column($reservationist, 'count'));
                foreach ($reservationist as $key => $reservationCount) {
                    if($reservationCount['count']==$min){
                       $data =  $reservationCount['staff_id'];
                    }
                }
            }elseif(isset($admin) && !empty($admin)){
                $min = min(array_column($admin, 'count'));
                foreach ($admin as $key => $adminCount) {
                    if($adminCount['count']==$min){
                       $data =  $adminCount['staff_id'];
                    }
                }
            }elseif(isset($superAdmin) && !empty($superAdmin)){
                $min = min(array_column($superAdmin, 'count'));
                foreach ($superAdmin as $key => $superAdminCount) {
                    if($superAdminCount['count']==$min){
                       $data =  $superAdminCount['staff_id'];
                    }
                }
            }else{
                $data=NULL;
            }
        }else{
           $data =NULL;
        }
        return $data;

    }*/


    public function actionList()
    {
        try {
            $tokendata = $this->checkAuth();
            if ($tokendata) {
                $result = [];
                $limit = LIMIT;
                if (isset($_GET['status'])) {

                    if ($_GET['status'] == Reservations::PENDING_CHECK_IN) {
                        $status = [Reservations::PENDING_CHECK_IN,Reservations::CHECKED_IN];
                    } else if ($_GET['status'] == Reservations::PROCESSING) {
                        $status = [Reservations::PROCESSING,Reservations::WAITING];
                    }else{
                        $status = [Reservations::PROCESSING,Reservations::WAITING];
                    }
                    if (isset($_GET['page'])) {
                        $offset = ($_GET['page'] - 1) * $limit;
                    } else {
                        $offset = 0;
                    }
                $reservations = Reservations::find()->where(['client_id' => $tokendata->user->profile->client_id, 'status' => $status])->orderBy('created_date DESC')->limit($limit)->offset($offset)->all();
                    foreach($reservations as $reservation){
                        array_push($result,Details::reservationInfo($reservation));
                    }
                    $code = 200;
                    $data = ['data'=>$result];

                } else {
                    $code = 500;
                    $data = $this->makeResponse($code, 'something went wrong');
                }

            } else {
                $code = 500;
                $data = $this->makeResponse($code, 'something went wrong');

            }
        } catch (Exception $e) {
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data));

    }
    public function actionTest(){
        print_r($this->autoAssign());
    }
    public function actionHistory()
    {
        try {
            $tokendata = $this->checkAuth();
            if ($tokendata) {
                $result = [];
                $limit = LIMIT;           
                    
                if (isset($_GET['page'])) {
                        $offset = ($_GET['page'] - 1) * $limit;
                } else {
                     $offset = 0;
                }
               $reservations = Reservations::find()->where(['IN', 'status', [Reservations::CANCEL_BY_CLIENT,Reservations::CANCEL_BY_CREWFACTS,Reservations::CHECK_OUT]])->limit($limit)->orderBy('created_date DESC')->offset($offset)->all();
                          
               foreach($reservations as $reservation){
                        array_push($result,Details::reservationInfo($reservation));
                }
                $code = 200;
                $data = ['data'=>$result];

            } else {
                $code = 500;
                $data = $this->makeResponse($code, 'something went wrong');

            }
        } catch (Exception $e) {                    
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data));

    }

    public function actionCheckIn()
    {
        try {
            $tokendata = $this->checkAuth();
            if(Yii::$app->request->isPost){
            $postdata = JSON::decode(file_get_contents("php://input"));
            $id = $postdata['reservation_id'];             
            
            $reservation = Reservations::find()->where(['id'=>$id])->one();
            $commit_flg = 0;
            if($reservation)
            {
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                if($reservation->status == Reservations::PENDING_CHECK_IN)
                {
                    $reservation->status = Reservations::CHECKED_IN;
                    if($reservation->save(false))
                    {
                        $rooms = $reservation->reservationRooms;
                        if($rooms){
                            foreach ($rooms as $room) {
                                $room->status = Reservations::CHECKED_IN;
                                if($room->save(false)){
                                    $commit_flg = 1;
                                }else{
                                    $transaction->rollBack();
                                }
                            } 
                            if($commit_flg == 1){
                                $transaction->commit(); 
                                $code = 200;
                                $data = $this->makeResponse($code, 'Checked in successfully.', Details::reservationInfo($reservation));
                            }                       
                        }else{
                            $transaction->commit(); 
                            $code = 200;
                            $data = $this->makeResponse($code, 'Checked in successfully.', Details::reservationInfo($reservation));
                       } 
                    }else{
                        $transaction->rollBack();
                        $code = 400;
                        $data = $this->makeResponse($code, 'Error occured while check in.');
                                   
                    }
                }else{
                    $code = 400;
                    $data = $this->makeResponse($code, 'unathorised access');
                }
            }else{
                $code = 400;
                $data = $this->makeResponse($code, 'No reservation exists with this reservation id');
            }
            }else{
                $code = 400;
                $data = $this->makeResponse($code, $this->getStatusCodeMessage($code));

            }

        } catch (Exception $e) {
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data));

    }


    public function actionCheckOut()
    {
        try {
            $tokendata = $this->checkAuth();
            if(Yii::$app->request->isPost){
            $postdata = JSON::decode(file_get_contents("php://input"));
            $id = $postdata['reservation_id'];
            $reservation = Reservations::find()->where(['id'=>$id])->one();

            $commit_flg = 0;
            if($reservation)
            {
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                if($reservation->status == Reservations::CHECKED_IN)
                {
                    $reservation->status = Reservations::CHECK_OUT;
                    if($reservation->save(false))
                    {
                        $rooms = $reservation->reservationRooms;
                        if($rooms){
                           foreach ($rooms as $room) {
                                $room->status = Reservations::CHECK_OUT;
                                 if($room->save(false)){
                                    $commit_flg = 1;
                                }else{
                                    $transaction->rollBack();
                                }
                            } 
                            if($commit_flg == 1){
                                $transaction->commit(); 
                                $code = 200;
                                $data = $this->makeResponse($code, 'Checked out successfully.',Details::reservationInfo($reservation));
                            } 
                        }else{
                            $transaction->commit(); 
                            $code = 200;
                            $data = $this->makeResponse($code, 'Checked out successfully.',Details::reservationInfo($reservation));
                        }
                    }else{

                        $transaction->rollBack();
                        $code = 400;
                        $data = $this->makeResponse($code, 'Error occurred in check out.');

                    }
                        
                }else{
                    $code = 400;
                    $data = $this->makeResponse($code, 'Could not check out because it is not in checked in mode.');

                }
            }else{
                $code = 400;
                $data = $this->makeResponse($code, 'No reservation exists with this reservation id.');
            }
            }else{
                $code=400;
                $this->makeResponse($code,$this->getStatusCodeMessage($code));
            }
        } catch (Exception $e) {
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data));

    }

    public function actionCancel()
    {
        try {
            $tokendata = $this->checkAuth();
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong!');
            if (Yii::$app->request->isPost) {
                $postdata = JSON::decode(file_get_contents("php://input"));
                $id = $postdata['reservation_id'];
                $reservation = Reservations::find()->where(['id' => $id])->one();
                $commit_flg = 0;
                if ($reservation) {
                    $connection = \Yii::$app->db;
                    $transaction = $connection->beginTransaction();
                    if ($reservation->status == Reservations::CANCEL_BY_CLIENT) {
                        $code = 400;
                        $data = $this->makeResponse($code, 'Already cancelled by client');
                    } elseif ($reservation->status == Reservations::CANCEL_BY_CREWFACTS) {
                        $code = 400;
                        $data = $this->makeResponse($code, 'Already cancelled by crewfacts');
                    } else {
                        $reservation->status = Reservations::CANCEL_BY_CLIENT;
                        if ($reservation->save(false)) {
                            $rooms = $reservation->reservationRooms;
                            if ($rooms) {
                                foreach ($rooms as $room) {
                                    $room->status = Reservations::CANCEL_BY_CLIENT;
                                    if ($room->save(false)) {
                                        $commit_flg = 1;
                                    } else {
                                        $transaction->rollBack();
                                    }
                                }
                                if ($commit_flg == 1) {
                                    $transaction->commit();
                                    $code = 200;
                                    $data = $this->makeResponse($code, 'Reservation cancelled successfully.',Details::reservationInfo($reservation));
                                }
                            } else {
                                $transaction->commit();
                                $code = 200;
                                $data = $this->makeResponse($code, 'Reservation cancelled successfully.',Details::reservationInfo($reservation));
                            }
                        } else {
                            $code = 400;
                            $data = $this->makeResponse($code, 'Error occured while cancelling reservation.');
                        }
                    }

                } else {
                    $code = 400;
                    $data = $this->makeResponse($code, 'No reservation exists with this reservation id.');
                }
            } else {
                $code = 400;
                $this->makeResponse($code, $this->getStatusCodeMessage($code));
            }
        } catch (Exception $e) {
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data));
    }

    /*public function actionExtendDate(){
        try {
            $tokendata = $this->checkAuth();
            if(Yii::$app->request->isPost){
                $postdata = JSON::decode(file_get_contents("php://input"));
                $id = $postdata['room_id'];
                $model = ReservationRooms::findOne($id);
                $main_array = Yii::$app->commonfunction->createDateRangeArray($model->check_in_date,$model->check_out_date);
                array_pop($main_array);
                if(isset($postdata['room_id'])) {
                    $newarray = Yii::$app->commonfunction->createDateRangeArray(date('Y-m-d', strtotime($postdata['check_in_date'])), date('Y-m-d', strtotime($postdata['check_out_date'])));
                    array_pop($newarray);
                    $difference = array_merge(array_diff($newarray, $main_array), array_diff($main_array, $newarray));
                    if ($difference) {
                        foreach ($difference as $date) {
                            $room = RoomsData::findOne(['reservation_rooms_id' => $id, 'date_at_facility' => $date]);
                            if ($room) {
                                $room->delete();
                            } else {
                                $new_room = new RoomsData();
                                $new_room->date_at_facility = $date;
                                $new_room->reservation_rooms_id = $id;
                                $new_room->room_no = NULL;
                                $new_room->buy_rate = NULL;
                                $new_room->sell_rate = NULL;
                                $new_room->cc_db = NULL;
                                $new_room->auth = NULL;
                                $new_room->bar = NULL;
                                $new_room->expiry = NULL;
                                $new_room->cvc = NULL;
                                $new_room->fees = NULL;
                                $new_room->tax_type = NULL;
                                $new_room->tax = NULL;
                                $new_room->confirmation_no = NULL;
                                $new_room->is_sent = 0;
                                $new_room->media_id = NULL;
                                $new_room->save(false);
                            }
                        }
                        $model->check_in_date = date('Y-m-d', strtotime($postdata['check_in_date']));
                        $model->check_out_date = date('Y-m-d', strtotime($postdata['check_out_date']));
                        $model->save(false);
                        $code = 200;
                        $result = Details::getRoomInfo($model);
                        $data = ['data'=>$result];
                        $this->sendResponse($code, JSON::encode($data));
                    }

                }else{
                    $code = 400;
                    $this->makeResponse($code,'Bad Parameter');
                }

            }else{
                $code = 400;
                $this->makeResponse($code,'Wrong Method, use post instead');
            }
        }  catch (Exception $e) {
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data));
    }*/

    public function actionExtendDate()
    {
        $tokendata = $this->checkAuth();
        $code = 500;
        $data = $this->makeResponse($code, 'something went wrong!');
        if (Yii::$app->request->isPost) {
            $postdata = JSON::decode(file_get_contents("php://input"));
            $id = $postdata['room_id'];
            $model = ReservationRooms::findOne($id);
            $request = new ReservationExtendRequest();
            if ($model) {
                $request->reservation_id = $model->reservation_id;
                $request->reservation_room_id = $model->id;
                $request->check_in_date = date('Y-m-d', strtotime($postdata['check_in_date']));
                $request->check_out_date = date('Y-m-d', strtotime($postdata['check_out_date']));
                $request->extend_type = ReservationExtendRequest::EXTEND_DATE;
                $request->requested_by = $tokendata->user_id;
                $request->request_date = time();
                if ($request->validate()) {
                    $request->save();
                    WebNotification::add($model->reservation_id, 2, $model->reservation->client_id, $model->reservation->staff_id);
                    $code = 200;
                    $data = $this->makeResponse($code, "request made successfully", Details::reservationInfo(Reservations::findOne($model->reservation_id)));
                } else {
                    $this->errorResponder($postdata, $request->getErrors());
                }

            } else {
                $code = 404;
                $data = $this->makeResponse($code, 'Room not found');
            }


        } else {
            $code = 400;
            $data = $this->makeResponse($code, 'Wrong Method, use post instead');
        }
        $this->sendResponse($code, JSON::encode($data));
    }
    public function actionAddRooms(){
        $tokendata = $this->checkAuth();
        $code = 500;
        $data = $this->makeResponse($code, 'something went wrong!');
        if (Yii::$app->request->isPost) {
            $postdata = JSON::decode(file_get_contents("php://input"));
            $id = $postdata['reservation_id'];
            $model = Reservations::findOne($id);
            $request = new ReservationExtendRequest();
            if ($model) {
                $request->reservation_id = $model->id;
                $request->reservation_room_id = NULL;
                $request->check_in_date = date('Y-m-d', strtotime($postdata['contract_info']['check_in_date']));
                $request->check_out_date = date('Y-m-d', strtotime($postdata['contract_info']['check_out_date']));
                $request->extend_type = ReservationExtendRequest::ADD_ROOM;
                $request->single_room = $postdata['contract_info']['single_room'];
                $request->double_room = $postdata['contract_info']['double_room'];
                $request->occupan_info = json_encode($postdata['occupant_info']);
                $request->requested_by = $tokendata->user_id;
                $request->request_date = time();
                if ($request->validate()) {
                    $request->save();
                    //WebNotification::add($model->reservation_id, 2, $model->reservation->client_id, $model->reservation->staff_id);
                    $code = 200;
                    $data = $this->makeResponse($code, "request made successfully", Details::reservationInfo($model));
                } else {
                    $this->errorResponder($postdata, $request->getErrors());
                }

            } else {
                $code = 404;
                $data = $this->makeResponse($code, 'Room not found');
            }


        } else {
            $code = 400;
            $data = $this->makeResponse($code, 'Wrong Method, use post instead');
        }
        $this->sendResponse($code, JSON::encode($data));
    }


}