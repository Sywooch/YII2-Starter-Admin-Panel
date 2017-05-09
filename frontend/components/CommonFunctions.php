<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 16/2/16
 * Time: 4:30 PM
 */


namespace frontend\components;


use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;

class CommonFunctions extends Component
{
    public function welcome() {
        echo "Hello..Welcome to MyComponent";
    }

    public function sendActivationEmail($user) {
        $activation_link = BASE_URL.Url::to(['user/activation','code'=>$user->activation_code,'id'=>$user->id]);
        $content_array = ['email'=>$user->email,'activation'=>$activation_link];

        return $this->sendEmail('activation',$content_array,$user->email,"Please verify your email address");
    }

    public function sendEmail($view,$content_array,$to_email,$subject) {

        return \Yii::$app->mailer->compose($view, $content_array)
               ->setFrom(["rohan@thetatechnolabs.com" => 'Developer At boo-bar'])
               ->setTo($to_email)
               ->setSubject($subject)
               ->send();


    }
    public function getGenderName($gender){

        if($gender == 0){
            return "Not Specified";
        }elseif($gender == 1){
            return "Male";
        }elseif($gender){
            return "Female";
        }
    }




}