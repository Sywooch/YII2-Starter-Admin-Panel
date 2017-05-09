<?php

namespace frontend\modules\expedia\controllers;

use common\models\ExpediaIternary;
use common\models\Reservations;
use frontend\components\Logger;
use frontend\components\RestController;
use yii\helpers\Json;
use linslin\yii2\curl;

/**
 * Default controller for the `expedia` module
 */
class BookReservationController extends RestController
{
    /**
     * Get list of hotels
     * @return json
     */
    public function actionIndex()
    {
        $device = $this->checkAuth();
        try {
            $postdata = JSON::decode(file_get_contents("php://input"));
            $config = $this->expediaConfig();
            $rooms = $this->generateRoomInfo($postdata['occupant_info']);
            unset($postdata['occupant_info']);
            $request = array_merge($postdata, $config);
            $final_request = array_merge($request, $rooms);
            Logger::add($final_request,'request');

            $curl = new curl\Curl();
            $data = $curl->setOption(
                CURLOPT_POSTFIELDS,
                http_build_query($final_request))
                ->post($this->ean_secured_base_url . "res");
            Logger::add($data,'response');

            $code = 200;
            if($data){
                $final =  Json::decode($data);
                if(!array_key_exists("EanWsError",$final['HotelRoomReservationResponse'])){
                    $ex = new ExpediaIternary();
                    $ex->user_id = $device->user_id;
                    $ex->iternary_id = $final['HotelRoomReservationResponse']['itineraryId'];
                    $ex->hotel_name = $final['HotelRoomReservationResponse']['hotelName'];
                    $ex->hotel_name = $final['HotelRoomReservationResponse']['hotelName'];
                    $ex->no_rooms = $final['HotelRoomReservationResponse']['numberOfRoomsBooked'];
                    $ex->check_in_date = date("Y-m-d", strtotime($final['HotelRoomReservationResponse']['arrivalDate']));
                    $ex->check_out_date = date("Y-m-d", strtotime($final['HotelRoomReservationResponse']['departureDate']));
                    $ex->user_email = $postdata['email'];
                    $ex->created_at = time();
                    $ex->save(false);
                }
            }

        } catch (\Exception $e) {
            $code = 500;
            $data = Json::encode($this->makeResponse($code, 'something went wrong'));
        }
        $this->sendResponse($code, $this->filterdata($data));
    }

    protected function generateRoomInfo($roominfo)
    {
        $single = isset($roominfo['single_room'])?$roominfo['single_room']:[];
        $double = isset($roominfo['double_room'])?$roominfo['double_room']:[];
        $result = array();
        $total = sizeof($single) + sizeof($double);
        for ($s = 1; $s <= $total; $s++) {
            if ($s <= sizeof($single)) {
                foreach ($single as $sin) {
                    $first_name = $sin[0]['first_name'];
                    $last_name = $sin[0]['last_name'];
                    $bed_type = $sin['bed_type'];
                }
                $result = Reservations::array_push_assoc($result, 'room' . $s, 1);
                $result = Reservations::array_push_assoc($result, 'room' . $s . 'FirstName', $first_name);
                $result = Reservations::array_push_assoc($result, 'room' . $s . 'LastName', $last_name);
                $result = Reservations::array_push_assoc($result, 'room' . $s . 'BedTypeId', $bed_type);
                $result = Reservations::array_push_assoc($result, 'room' . $s . 'SmokingPreference', 'NS');
            } else {
                foreach ($double as $dou) {
                    $first_name = $dou[0]['first_name'];
                    $last_name = $dou[0]['last_name'];
                    $bed_type = $dou['bed_type'];
                }
                $result = Reservations::array_push_assoc($result, 'room' . $s, 2);
                $result = Reservations::array_push_assoc($result, 'room' . $s . 'FirstName', $first_name);
                $result = Reservations::array_push_assoc($result, 'room' . $s . 'LastName', $last_name);
                $result = Reservations::array_push_assoc($result, 'room' . $s . 'BedTypeId', $bed_type);
                $result = Reservations::array_push_assoc($result, 'room' . $s . 'SmokingPreference', 'NS');
            }
        }
        return $result;
    }

    protected function array_blacklist_assoc(Array $array1, Array $array2)
    {

        foreach ($array2 as $key => $value) {
            unset($array1[$key]);
        }
        return $array1;

    }

}