<?php
namespace frontend\controllers;

use common\models\Reservations;
use frontend\components\CustController;
use frontend\components\Logger;
use frontend\models\BookReservation;
use frontend\models\BookReservationForm;
use frontend\models\JobSiteForm;
use frontend\models\Url;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use linslin\yii2\curl\Curl;

/**
 * Site controller
 */
class EliteController extends CustController
{

    /**
     * Displays Job Site form.
     *
     * @return mixed
     */
    public function actionSearchHotel()
    {

        $model = new JobSiteForm();
        if(isset($_POST['JobSiteForm'])){
            $req_arr = [
                'arrivalDate'=>$_POST['JobSiteForm']['check_in_date'],
                'departureDate'=>$_POST['JobSiteForm']['check_out_date'],
                'double_room'=>$_POST['JobSiteForm']['double_rooms'],
                'single_room'=>$_POST['JobSiteForm']['single_rooms'],
                'stateProvinceCode'=>$_POST['JobSiteForm']['state'],
                'city'=>$_POST['JobSiteForm']['city'],
                'customerUserAgent'=> $_SERVER['HTTP_USER_AGENT'],
                'customerIpAddress'=> $_SERVER['REMOTE_ADDR'],
                'customerSessionId'=> $this->GUIDv4(),
            ];
            Yii::$app->session->set('request_data',$req_arr);
            Yii::$app->session->set('guid',$this->GUIDv4());
            return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('elite/hotel-list'));

        }
        return $this->render('index',['model'=>$model]);
    }

    /**
     * Displays Job Site form.
     *
     * @return mixed
     */
    public function actionHotelList()
    {
        if(Yii::$app->session->get('request_data')){

            $req_arr = Yii::$app->session->get('request_data');
            Logger::add($req_arr,'request');
            $request_json = Json::encode($req_arr);

            $curl = new Curl();
            $curl->setOption(CURLOPT_HTTPHEADER,[
                Url::ACCESS_TOKEN.':'.Yii::$app->session->get('access_token')]
            );
            $curl->setOption(CURLOPT_SSL_VERIFYPEER,false);
            $curl->setOption(CURLOPT_POSTFIELDS,$request_json);
            $response = $curl->post(Url::HOTEL_SEARCH);
            $data = Json::decode($response);
            print_r($data);die;
            Logger::add($data,'response');

            if(isset($data['error'])){
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'alert-danger',
                    'message' => "Something went wrong!!",
                    'title' => 'Error',
                ]);
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['elite/search-hotel']));
            }
            if(array_key_exists("EanWsError",$data['HotelListResponse'])){
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'alert-danger',
                    'message' => $data['HotelListResponse']['EanWsError']['presentationMessage'],
                    'title' => 'Hotel not searched',
                ]);
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['elite/search-hotel']));

            }else{
                $model = Json::decode($response);
                Yii::$app->session->set("session_id",$model['HotelListResponse']['customerSessionId']);
            }

        }else{
           return $this->goBack();
        }

        return $this->render('hotel-list',['model'=>$model]);
    }
    public function actionHotelDetails($id){
        $curl = new Curl();
        $curl->setOption(CURLOPT_HTTPHEADER,[
                Url::ACCESS_TOKEN.':'.Yii::$app->session->get('access_token')]
        );
        $curl->setOption(CURLOPT_SSL_VERIFYPEER,false);
        $response = $curl->get(Url::HOTEL_INFO.'/'.$id.'?customerSessionId='.Yii::$app->session->get('session_id'));
        $data = Json::decode($response);
        if(array_key_exists("EanWsError",$data['HotelInformationResponse'])){
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'alert-danger',
                'message' => $data['HotelInformationResponse']['EanWsError']['presentationMessage'],
                'title' => 'Something went wrong',
            ]);
            return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['elite/hotel-list']));

        }else{
            $model = Json::decode($response);
            Yii::$app->session->set('hotel_data',$model);
        }

        return $this->render('hotel-details',['model'=>$model]);
    }
    public function actionRoomAvailability($id){
        if(Yii::$app->session->get('request_data')){
            $request_data = Yii::$app->session->get('request_data');
            $req_arr = [
                'arrivalDate'=>$request_data['arrivalDate'],
                'departureDate'=>$request_data['departureDate'],
                'customerUserAgent'=> $_SERVER['HTTP_USER_AGENT'],
                'customerIpAddress'=> $_SERVER['REMOTE_ADDR'],
                'customerSessionId'=> Yii::$app->session->get('session_id'),
                'hotelId'=>$id,
                'includeDetails'=>true,
                'double_room'=>$request_data['double_room'],
                'single_room'=>$request_data['single_room'],
            ];
            $request_json = Json::encode($req_arr);
            $curl = new Curl();
            $curl->setOption(CURLOPT_HTTPHEADER,[
                    Url::ACCESS_TOKEN.':'.Yii::$app->session->get('access_token')]
            );
            $curl->setOption(CURLOPT_SSL_VERIFYPEER,false);
            $curl->setOption(CURLOPT_POSTFIELDS,$request_json);
            $response = $curl->post(Url::ROOM_AVAILABILITY);
            $data = Json::decode($response);
            if(array_key_exists("EanWsError",$data['HotelRoomAvailabilityResponse'])){
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'alert-danger',
                    'message' => $data['HotelRoomAvailabilityResponse']['EanWsError']['presentationMessage'],
                    'title' => 'Something went wrong',
                ]);
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['elite/hotel-details','id'=>$id]));

            }else{
                $model = Json::decode($response);
                Yii::$app->session->set('room_type_data_available',$model);
                $model['hotelId']=$id;
            }

        }else{
            return $this->goBack();
        }

        return $this->render('rooms',['model'=>$model]);
    }
    public function actionRoomDetail($id){
        if(isset($_POST['room_type_data'])){
            $model = Json::decode($_POST['room_type_data']);
            Yii::$app->session->set('room_type_data',$model);
            $model['request_info'] = Yii::$app->session->get('request_data');
            $model['hotelId']=$id;
        }else{
            return $this->goBack();
        }

        return $this->render('room-detail',['model'=>$model]);
    }

    public function actionFeelOccupantInfo($id){
        if(Yii::$app->session->get('room_type_data')){
            $model = Yii::$app->session->get('room_type_data');
            $model['request_info'] = Yii::$app->session->get('request_data');
            if(isset($_POST['occupant_info'])){
               Yii::$app->session->set('occupant_info',$_POST['occupant_info']);
               return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['elite/payment-types','id'=>$id]));
            }

        }else{
            return $this->goBack();
        }

        return $this->render('occupant-info',['model'=>$model]);
    }
    public function actionPaymentTypes($id){

        if(isset($_POST['card_data'])){
            Yii::$app->session->set('card_data',$_POST['card_data']);
            return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['elite/book-reservation','id'=>$id]));
        }else{
            $type = Yii::$app->session->get('room_type_data');
            $req_arr = [
                'hotelId'=>$id,
                'customerUserAgent'=> $_SERVER['HTTP_USER_AGENT'],
                'customerIpAddress'=> $_SERVER['REMOTE_ADDR'],
                'customerSessionId'=> Yii::$app->session->get('session_id'),
                'supplierType'=>$type['supplierType'],
                'rateType'=>$type['RateInfos']['RateInfo']['rateType']
            ];
            $request_json = Json::encode($req_arr);
            $curl = new Curl();
            $curl->setOption(CURLOPT_HTTPHEADER,[
                    Url::ACCESS_TOKEN.':'.Yii::$app->session->get('access_token')]
            );
            $curl->setOption(CURLOPT_SSL_VERIFYPEER,false);
            $curl->setOption(CURLOPT_POSTFIELDS,$request_json);
            $response = $curl->post(Url::PAYMENT_TYPES);
            $data = Json::decode($response);
            if(array_key_exists("EanWsError",$data['HotelPaymentResponse'])){
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'alert-danger',
                    'message' => $data['HotelPaymentResponse']['EanWsError']['presentationMessage'],
                    'title' => 'Something went wrong',
                ]);
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['elite/payment-types','id'=>$id]));

            }else{
                $model = Json::decode($response);
                $model['hotelId']=$id;
            }
        }


        return $this->render('payment-types',['model'=>$model]);
    }

    public function actionBookReservation($id){
        if(Yii::$app->session->get('card_data')){
            $model = new BookReservationForm();
            $model->credit_card_type = Yii::$app->session->get('card_data');
            $model->credit_card_type_disp = Yii::$app->session->get('card_data');
            if(isset($_POST['BookReservationForm'])){
               /* echo "<pre>";
                print_r($_POST);die;*/
                Yii::$app->session->set('post_data',$_POST);
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['elite/confirm-booking','id'=>$id]));
            }
        }else{
            return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['elite/payment-types','id'=>$id]));
        }
        return $this->render('book-reservation',['model'=>$model]);
    }
    public function actionConfirmBooking($id){
        $model = ['hotel_data'=>Yii::$app->session->get('hotel_data'),'post_data'=>Yii::$app->session->get('post_data'),'room'=>Yii::$app->session->get('room_type_data'),'request_data'=>Yii::$app->session->get('request_data'),'room_type_data_available'=>Yii::$app->session->get('room_type_data_available'),'id'=>$id];
        //print_r($model['room_type_data_available']);die;
        if(isset($_GET['paynow'])){
            $request_data =Yii::$app->session->get('request_data');
            $room_data = Yii::$app->session->get('room_type_data');
            $occupant_info = Yii::$app->session->get('occupant_info');
            $total = $request_data['double_room']+$request_data['single_room'];
            if($total == 1){
                $rate_key = $room_data['RateInfos']['RateInfo']['RoomGroup']['Room']['rateKey'];
                $chargeable_rate = $room_data['RateInfos']['RateInfo']['ChargeableRateInfo']['total'];
            }else{
                $rate_key = $room_data['RateInfos']['RateInfo']['RoomGroup']['Room'][0]['rateKey'];
                $chargeable_rate = $room_data['RateInfos']['RateInfo']['ChargeableRateInfo']['total'];

            }
            //$room_data['RateInfos']['RateInfo']['RoomGroup']['Room']['']
            $req_arr = [
                'hotelId'=>$id,
                'arrivalDate'=>$request_data['arrivalDate'],
                'departureDate'=>$request_data['departureDate'],
                'supplierType'=>$room_data['supplierType'],
                'customerUserAgent'=> $_SERVER['HTTP_USER_AGENT'],
                'customerIpAddress'=> $_SERVER['REMOTE_ADDR'],
                'customerSessionId'=> Yii::$app->session->get('session_id'),
                'rateKey'=>$rate_key,
                'rateCode'=>$room_data['rateCode'],
                'roomTypeCode'=>$room_data['roomTypeCode'],
                'chargeableRate'=>$chargeable_rate,
                'email'=>$model['post_data']['BookReservationForm']['email'],
                'firstName'=>$model['post_data']['BookReservationForm']['first_name'],
                'lastName'=>$model['post_data']['BookReservationForm']['last_name'],
                'homePhone'=>$model['post_data']['BookReservationForm']['home_phone'],
                'workPhone'=>$model['post_data']['BookReservationForm']['work_phone'],
                'creditCardType'=>$model['post_data']['BookReservationForm']['credit_card_type'],
                'creditCardNumber'=>$model['post_data']['BookReservationForm']['credit_card_number'],
                'creditCardIdentifier'=>$model['post_data']['BookReservationForm']['credit_card_identifier'],
                'creditCardExpirationMonth'=>$model['post_data']['BookReservationForm']['credit_card_exp_month'],
                'creditCardExpirationYear'=>$model['post_data']['BookReservationForm']['credit_card_exp_year'],
                'address1'=>$model['post_data']['BookReservationForm']['address'],
                'city'=>$model['post_data']['BookReservationForm']['city'],
                'affiliateConfirmationId'=> Yii::$app->session->get('guid'),
                'stateProvinceCode'=>$model['post_data']['BookReservationForm']['state'],
                'countryCode'=>$model['post_data']['BookReservationForm']['country'],
                'postalCode'=>$model['post_data']['BookReservationForm']['postal_code'],
                'occupant_info'=>$occupant_info,
            ];
            $request_json = Json::encode($req_arr);
            $curl = new Curl();
            $curl->setOption(CURLOPT_HTTPHEADER,[
                    Url::ACCESS_TOKEN.':'.Yii::$app->session->get('access_token')]
            );
            $curl->setOption(CURLOPT_SSL_VERIFYPEER,false);
            $curl->setOption(CURLOPT_POSTFIELDS,$request_json);
            $response = $curl->post(Url::BOOK_RESERVATION);
            $data = Json::decode($response);
            Yii::$app->session->set('confirmation_data',$data);
            if(array_key_exists("EanWsError",$data['HotelRoomReservationResponse'])){
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'alert-danger',
                    'message' => $data['HotelRoomReservationResponse']['EanWsError']['presentationMessage'],
                    'title' => 'Reservation Not setup',
                ]);
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['elite/book-reservation','id'=>$id]));

            }else{
                Yii::$app->session->set('final_data',$model);
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'alert-success',
                    'message' => 'Reservation Booked successfully',
                    'title' => 'Reservation booked successfully, You can check detail on "My Request"',
                ]);
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['elite/confirmation']));

            }
        }
        return $this->render('confirm-booking',['model'=>$model]);
    }

    public function actionConfirmation(){
        $model = Yii::$app->session->get('final_data');
        return $this->render('confirmation',['model'=>$model]);
    }

}
