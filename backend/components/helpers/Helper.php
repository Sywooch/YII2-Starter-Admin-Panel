<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 10/6/16
 * Time: 7:14 PM
 */
namespace backend\components\helpers;
use common\models\AppUserProfile;
use common\models\Media;
use common\models\SalonMedia;
use common\models\Service;
use Yii;

class Helper {

     public static function isMenuActive($controller,$action= ""){
         if(Yii::$app->controller->id == $controller){
             if($action == ""){
                 return "active";
             }else{
                 if(Yii::$app->controller->action->id == $action){
                     return "active";
                 }else{
                     return "";
                 }
             }

         }else{
             return "";
         }
     }

    public static function fileUpload($file,$attr_name,$pre_fix,$path = "",$is_form = true){

        $uploadPath = ($path)?Yii::$app->params['uploadPath'].$path.'/':Yii::$app->params['uploadPath'];
        if(!is_writable($uploadPath)){
            mkdir($uploadPath);
        }
        if($uploadPath){
            $ext = new \SplFileInfo(($is_form)?$file['name'][$attr_name]:$file['name']);
            $newname = $pre_fix.time().'.'.$ext->getExtension();
            $media = New Media();
            $media->file_name =  $newname;
            $media->file_path =  $uploadPath.$newname;
            $media->original_name = ($is_form)?$file['name'][$attr_name]:$file['name'];
            $media->file_url =($path)?Yii::$app->urlManager->createAbsoluteUrl('uploads/'.$path.'/'.$newname): Yii::$app->urlManager->createAbsoluteUrl('uploads'.'/'.$newname);
            $media->setDefaultValues();
            if(move_uploaded_file(($is_form)?$file['tmp_name'][$attr_name]:$file['tmp_name'],$media->file_path)){
                if($media->save()){
                    return $media->id;
                }else{
                    return false;
                }
            }else{
                    return false;
            }

        }else{
            return false;
        }

    }
    public static function getSelector(){
        $salon_media = SalonMedia::find()->all();
        $service = Service::find()->all();
        $user_profile = AppUserProfile::find()->all();
        $result= [];
        if($salon_media){
            array_push($result,[SalonMedia::tableName(),'media_id']);
        }
        if($service){
            array_push($result,[Service::tableName(),'service_media']);
        }
        if($user_profile){
            array_push($result,[AppUserProfile::tableName(),'profile_media_id']);
        }
        return $result;
    }
    public static function getData($target,$selectors)
    {
        $target_tbl_name = current($target);
        $target_pk = end($target);
        $query_str = "SELECT DISTINCT `" . $target_tbl_name . "`.`" . $target_pk . "` FROM " . $target_tbl_name;
        foreach ($selectors as $selector) {
            $query_str .= " INNER JOIN ".$selector[0]." ON `" . $target_tbl_name . "`.`" . $target_pk . "` NOT IN (SELECT `".$selector[0]."` . `".end($selector)."` FROM `".$selector[0]."`)";
        }
        echo $query_str;

    }
}