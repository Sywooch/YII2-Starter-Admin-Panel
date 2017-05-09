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
class PaymentController extends RestController {
    /**
     * Get list of hotels
     * @return json
     */
    public function actionTypes()  {
    	$device = $this->checkAuth();     	
        try {

            $postdata = JSON::decode(file_get_contents("php://input"));
            $config = $this->expediaConfig();
            $final_request = array_merge($config, $postdata);
            Logger::add($final_request,'request');
            $curl = new curl\Curl();
            $data = $curl->get($this->ean_secured_base_url . 'paymentInfo?'.http_build_query($final_request));
            Logger::add($data,'response');
            $code = 200;
         } catch (\Exception $e) {
            $code = 500;
            $data = Json::encode($this->makeResponse($code, 'something went wrong'));
        }
        $this->sendResponse($code, $this->filterdata($data));
    }
}