<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 22/9/16
 * Time: 5:20 PM
 */
namespace backend\components\notify;

use yii\base\Component;
use backend\components\notify\Firebase;
class Notification extends Component {

    private $android;
    private $ios;

    /**
     * Send Push
     * @param object user, String title,String message,data whcih is need to send
     * */
    public function SendPush($user,$title,$message,$data,$background=false,$image=''){
        $android_ids = [];
        $ios_ids = [];
        foreach($user->devices as $device){
            if($device->is_login){
                if(strtolower($device->device_platform) =='android'){
                    if($device->device_token != 'android' || $device->device_token != 'test token')
                        array_push($android_ids,$device->device_token);
                }elseif(strtolower($device->device_platform) =='ios'){
                    if($device->device_token != 'ios' || $device->device_token != 'token')
                        array_push($ios_ids,$device->device_token);
                }
            }
        }
        if(($key = array_search('test token', $android_ids)) !== false) {
            unset($android_ids[$key]);
        }
        if(($keyi = array_search('token', $ios_ids)) !== false) {
            unset($ios_ids[$keyi]);
        }
        $fcm = new Firebase();
        foreach($android_ids as $ai){
            $fcm->send([$ai],$fcm->getPush($title,$message,$background,$image,$data));
        }
        $apns = new Apns();
        $apns->sendMultiple($ios_ids,$message,$data);

    }



}