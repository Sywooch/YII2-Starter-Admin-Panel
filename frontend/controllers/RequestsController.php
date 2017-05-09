<?php
namespace frontend\controllers;

use common\models\ExpediaIternary;
use frontend\components\CustController;
use frontend\models\Url;
use linslin\yii2\curl\Curl;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class RequestsController extends CustController
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model =  ExpediaIternary::find()->where(['user_id'=>Yii::$app->user->identity->id])->orderBy(['(created_at)' => SORT_DESC])->all();
        if(isset($_POST['iternary_id'])){
            $req_arr =[
                'itineraryId'=>$_POST['iternary_id'],
                'email'=>$_POST['user_email'],
                'customerUserAgent'=> $_SERVER['HTTP_USER_AGENT'],
                'customerIpAddress'=> $_SERVER['REMOTE_ADDR'],
                'customerSessionId'=> Yii::$app->session->get('session_id'),
            ];
            Yii::$app->session->set('iternary',$req_arr);
            Yii::$app->session->set('room_no',$_POST['no_rooms']);
            return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('requests/detail'));
        }
        return $this->render('itinerary',['model'=>$model]);
    }

    public function actionDetail(){
        if(Yii::$app->session->get('iternary')){
            if(isset($_POST['cancel'])){
                Yii::$app->session->set('cancel_item',$_POST['cancel']);
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['requests/cancel','id'=>$_POST['cancel']['iternary_id']]));
            }

            $request_json = Json::encode(Yii::$app->session->get('iternary'));
            $curl = new Curl();
            $curl->setOption(CURLOPT_HTTPHEADER,[
                    Url::ACCESS_TOKEN.':'.Yii::$app->session->get('access_token')]
            );
            $curl->setOption(CURLOPT_SSL_VERIFYPEER,false);
            $curl->setOption(CURLOPT_POSTFIELDS,$request_json);
            $response = $curl->post(Url::ITERNARY_VIEW);
            $data = Json::decode($response);
            /*echo "<pre>";
            print_r($data);die;*/
            if(array_key_exists("EanWsError",$data['HotelItineraryResponse'])){
                Yii::$app->getSession()->setFlash('danger', [
                    'type' => 'alert-danger',
                    'message' => $data['HotelItineraryResponse']['EanWsError']['presentationMessage'],
                    'title' => 'Something went wrong',
                ]);
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['requests']));
            }else{
                $model = $data;
                $model['no_room'] = Yii::$app->session->get('room_no');
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', [
                'type' => 'alert-danger',
                'message' => "Itinerary not found",
                'title' => 'Something went wrong',
            ]);
            return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['requests']));

        }
        return $this->render('iternary_detail',['model'=>$model]);

    }

    public function actionCancel($id){
        if(Yii::$app->session->get('cancel_item')){

            $model =  Yii::$app->session->get('cancel_item');
            if($model['iternary_id'] == $id){
                if(isset($_POST['canceled'])){
                    $req_arr = [
                        'customerUserAgent'=> $_SERVER['HTTP_USER_AGENT'],
                        'customerIpAddress'=> $_SERVER['REMOTE_ADDR'],
                        'customerSessionId'=> Yii::$app->session->get('session_id'),
                        'itineraryId'=>$model['iternary_id'],
                        'email'=>$model['email'],
                        'reason'=>$_POST['canceled']['reason'],
                        'confirmationNumber'=>Json::decode($model['confirmationNumber']),
                    ];
                    $request_json = Json::encode($req_arr);
                    $curl = new Curl();
                    $curl->setOption(CURLOPT_HTTPHEADER,[
                            Url::ACCESS_TOKEN.':'.Yii::$app->session->get('access_token')]
                    );
                    $curl->setOption(CURLOPT_SSL_VERIFYPEER,false);
                    $curl->setOption(CURLOPT_POSTFIELDS,$request_json);
                    $response = $curl->post(Url::ITERNARY_CANCEL);
                    $data = Json::decode($response);
                    if(array_key_exists("EanWsError",$data['HotelRoomCancellationResponse'])){
                        Yii::$app->getSession()->setFlash('danger', [
                            'type' => 'alert-danger',
                            'message' => $data['HotelRoomCancellationResponse']['EanWsError']['presentationMessage'],
                            'title' => 'Something went wrong',
                        ]);
                        return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['requests']));
                    }else{
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'alert-success',
                            'message' => "Itinerary is cancelled successfully.",
                            'title' => 'Cancelled Successfully  ',
                        ]);
                        return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['requests']));

                    }

                }
            }else{
                Yii::$app->getSession()->setFlash('danger', [
                    'type' => 'alert-danger',
                    'message' => "Itinerary not found",
                    'title' => 'Something went wrong',
                ]);
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['requests']));
            }

        }
        return $this->render('cancel',['model'=>$model]);
    }

}
