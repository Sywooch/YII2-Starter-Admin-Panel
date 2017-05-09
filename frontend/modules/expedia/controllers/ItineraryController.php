<?php

namespace frontend\modules\expedia\controllers;

use common\models\ExpediaIternary;
use common\models\Reservations;
use frontend\components\RestController;
use yii\helpers\Json;
use linslin\yii2\curl;

/**
 * Default controller for the `expedia` module
 */
class ItineraryController extends RestController {
	 /**
     * Get list of hotels
     * @return json
     */
     public function actionView()  {
      	$device = $this->checkAuth();     	
        try {
        	$config = $this->expediaConfig();
        	$postdata = JSON::decode(file_get_contents("php://input"));
        	$final_request = array_merge($config, $postdata);
            $curl = new curl\Curl();
            $data = $curl->get($this->ean_base_url . 'itin?'.http_build_query($final_request));
            $code = 200;

        } catch (\Exception $e) {
            $code = 500;
            $data = Json::encode($this->makeResponse($code, 'something went wrong'));
        }
        $this->sendResponse($code, $this->filterdata($data));
    }
    public function actionList()  {
        $device = $this->checkAuth();
        try {
            $config = $this->expediaConfig();
            $postdata = JSON::decode(file_get_contents("php://input"));
            $final_request = array_merge($config, $postdata);
          //  print_r($postdata);die;
            $curl = new curl\Curl();
            $curl->setOption(CURLOPT_SSL_VERIFYPEER,false);
            $data = $curl->get($this->ean_base_url . 'itin?'.http_build_query($final_request));
            $code = 200;

        } catch (\Exception $e) {
            $code = 500;
            $data = Json::encode($this->makeResponse($code, 'something went wrong'));
        }
        $this->sendResponse($code, $this->filterdata($data));
    }

     public function actionCancel()  {
      	$device = $this->checkAuth();     	
        try {
        	$config = $this->expediaConfig();
        	$postdata = JSON::decode(file_get_contents("php://input"));
            $req_arr =[];
            $response =[];
            $curl = new curl\Curl();

            foreach($postdata['confirmationNumber'] as $confirm){
                    $req_arr['itineraryId'] = $postdata['itineraryId'];
                    $req_arr['email'] = $postdata['email'];
                    $req_arr['reason'] = $postdata['reason'];
                    $req_arr['confirmationNumber'] = $confirm;
                    $final_request = array_merge($config, $req_arr);
                    $data = $curl->get($this->ean_base_url . 'cancel?'.http_build_query($final_request));
                array_push($response,$data);
            }
            $model = ExpediaIternary::findOne(['iternary_id'=>$postdata['itineraryId']]);
            if($model){
                $model->delete();
            }
            $data = $response[0];
            $code =200;

        } catch (\Exception $e) {
            $code = 500;
            $data = Json::encode($this->makeResponse($code, 'something went wrong'));
        }
        $this->sendResponse($code, $this->filterdata($data));
    }
    public function actionGetList(){

        $device = $this->checkAuth();
        try {
            $result=[];
            $models = ExpediaIternary::findAll(['user_id'=>$device->user_id]);
            foreach($models as $model){
                array_push($result,[
                    'itinerary_id'=>$model->iternary_id,
                    'check_in_date'=>strtotime($model->check_in_date),
                    'check_out_date'=>strtotime($model->check_out_date),
                    'hotel_name'=>$model->hotel_name,
                    'user_email'=>$model->user_email,
                    'no_room'=>$model->no_rooms,
                    'created_at'=>$model->created_at
                ]);
            }
            $code = 200;
            $data = ['data'=>$result];

        } catch (\Exception $e) {
            $code = 500;
            $data = $this->makeResponse($code, 'something went wrong');
        }
        $this->sendResponse($code, JSON::encode($data));

    }

}