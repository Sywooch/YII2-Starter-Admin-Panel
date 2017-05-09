<?php

namespace frontend\modules\expedia\controllers;

use common\models\Reservations;
use frontend\components\Logger;
use frontend\components\RestController;
use yii\helpers\Json;
use linslin\yii2\curl;

/**
 * Default controller for the `expedia` module
 */
class RoomController extends RestController {
    /**
     * Get list of hotels
     * @return json
     */
    public function actionImages($id)  {
    	$device = $this->checkAuth();     	
        try {
        	$config = $this->expediaConfig();
            $final_request = array_merge($config,array('hotelId'=>$id));
            Logger::add($final_request,'request');
            $curl = new curl\Curl();
            $data = $curl->get($this->ean_secured_base_url . 'roomImages?'.http_build_query($final_request));
            Logger::add($data,'response');
            $code = 200;
         } catch (\Exception $e) {
            $code = 500;
            $data = Json::encode($this->makeResponse($code, 'something went wrong'));
        }
        $this->sendResponse($code, $this->filterdata($data));
    }

    public function actionAvailability()  {
    	$device = $this->checkAuth();     	
        try {
        	$postdata = JSON::decode(file_get_contents("php://input"));
            $roominfo = array('single_room' => $postdata['single_room'], 'double_room' => $postdata['double_room']);
            $config = $this->expediaConfig();
            $rooms = $this->getRooms($roominfo);
            $request = array_merge($this->array_blacklist_assoc($postdata,$roominfo),$config);
            $final_request = array_merge($request, $rooms);
            Logger::add($final_request,'request');
            $curl = new curl\Curl();
            $data = $curl->get($this->ean_base_url . 'avail?'.http_build_query($final_request));
            Logger::add($data,'response');

            $code = 200;

        } catch (\Exception $e) {
            $code = 500;
            $data = Json::encode($this->makeResponse($code, 'something went wrong'));
        }
        $this->sendResponse($code, $this->filterdata($data));
    }
    protected function getRooms($roominfo) {
        $single = $roominfo['single_room'];
        $double = $roominfo['double_room'];
        $result = array();
        $total = $single + $double;
        for ($s = 1; $s <= $total; $s++) {
            if ($s <= $single) {
                $result = Reservations::array_push_assoc($result, 'room' . $s, 1);
            } else {
                $result = Reservations::array_push_assoc($result, 'room' . $s, 2);
            }
        }
        return $result;
    }

    protected function array_blacklist_assoc(Array $array1, Array $array2) {

        foreach ($array2 as $key => $value) {
            unset($array1[$key]);
        }
        return $array1;

    }
}