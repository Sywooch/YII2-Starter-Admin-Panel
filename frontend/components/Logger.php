<?php
/**
 * Created by PhpStorm.
 * User: theta-php
 * Date: 15/2/17
 * Time: 1:03 PM
 */
namespace frontend\components;
use Yii;
class Logger {

    public static function add($data,$type){
           file_put_contents('webservice.log',print_r([$type,$data,Yii::$app->controller->id."/".Yii::$app->controller->action->id,date('d-m-Y h:i:s A')],true),FILE_APPEND);

    }
}