<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 16/2/16
 * Time: 4:30 PM
 */


namespace backend\components;


use backend\models\Menus;
use common\models\AppUserProfile;
use common\models\Media;
use common\models\queries\StaffUserProfileQuery;
use common\models\ReservationRooms;
use common\models\Reservations;
use common\models\StaffUserProfile;
use common\models\User;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class CommonFunctions extends Component
{
    public function welcome() {
        echo "Hello..Welcome to MyComponent";
    }

    public function sendEmail($view,$content_array,$subject) {

        return \Yii::$app->mailer->compose($view, $content_array)
               ->setFrom(["noreply@thetatechnolabs.com" => 'Theta Technolabs'.APP_NAME])
               ->setTo("test@thetatechnolabs.com")
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

    public function isLocked(){
        if(\Yii::$app->user->identity->screenlock){
            return true;
        }else{
            return false;
        }
    }

    /*
    *  for rights
     * @rohan
    */
    public function ifChecked($roledata,$setting){
        if(($roledata)){
            $flag =false;
            foreach($roledata as $r){
                if($r->role_id == $setting['role'] && $r->controller_id == $setting['controller'] && $r->action_id == $setting['action']){
                        $flag = true;
                }
            }
            return $flag;

        }else{
            return false;
        }
    }

    //check user is Admin
    public function isAdmin($id=""){
        $id = empty($id)?yii::$app->user->identity->getId():$id;
        if(!empty($id)){
            $user =  User::findOne(['id'=>$id]);
            if($user){

                if($user->role === ADMIN){
                    return true;
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

    //check user is super Admin
    public function isSuperAdmin($id=""){
        $id = empty($id)?yii::$app->user->identity->getId():$id;
        if(!empty($id)){
            $user = User::findOne(['id'=>$id]);
            if($user){
                if($user->role === SUPER_ADMIN){
                    return true;
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

    public function createPublicProfile($name,$id){

        $fontpath = Yii::getAlias('@backend').DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'fonts';
        $ThumbSize = array("0" => "120", "1" => "120");;
        $text = $name ;
        //Set the image width and height
        $width = $ThumbSize[0];
        $height = $ThumbSize[1];

        $colorArray = array('110, 176, 86', '150, 71, 71', '143, 56, 49', '144, 134, 189', '108, 168, 206', '232, 0, 135');
        $randKey = array_rand($colorArray, 1);
        $bgcolor = $colorArray[$randKey];
        list($r, $g, $b) = explode(",", $bgcolor);
        //creates a image handle
        $img = imagecreate($width, $height);

        //choose a bg color, u can play with the rgb values
        $background = imagecolorallocate($img, $r, $g, $b);
        //chooses the text color
        $text_colour = imagecolorallocate($img, 255, 255, 255);

        //sets the thickness/bolness of the line
        imagesetthickness($img, 3);

        //draws a line  params are (imgres,x1,y1,x2,y2,color)
        imageline($img, 20, 130, 165, 130, $text_colour);

        // place the font file in the same dir level as the php file
        $font = $fontpath.'/comic.ttf';
        // print_r($font);die;

        //this function sets the font size, places to the co-ords
        imagettftext($img, 35, 0, 22, 80, $text_colour, $font, $text);
        //places another text with smaller size
        //imagettftext($img, 16, 0, 10, 160, $text_colour, $font, 'User Name');

        $profile_pic = time() . "profile_pic";
        $imageName = $ThumbSize[0] . "x" . $ThumbSize[1] . "_" . $profile_pic;

        //header( 'Content-type: image/png' );
        //imagepng($img);exit;

        $uploadpath = Yii::getAlias('@backend').DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;
        if(!is_dir($uploadpath)){
            mkdir($uploadpath,0777);
        }
        $userpath = $uploadpath.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR;
        if(!is_dir($userpath)){
            mkdir($userpath,0777);
        }


        if(imagepng($img,$userpath.$imageName)){
          $media =  $this->uploadMedia($id,$userpath,$imageName);
            $this->updateProfilePic($id,$media->id);
        }

        //echo  exit;
        imagedestroy($img);
        return $imageName;
    }

    public function uploadMedia($id,$uploadPath,$imageName){
        $media = new Media();
        $media->file_name   = $imageName;
        $media->file_path   = $uploadPath.$imageName;
        $media->file_url    = Yii::$app->urlManager->createAbsoluteUrl('uploads/'.$id.'/'.$imageName);
        $media->created_by  =   $id;
        $media->original_name = $imageName;
        $media->staus       =   ACTIVE;
        $media->created_date  = time();
        $media->updated_date  = time();
        $media->updated_by = $id;
        $media->is_deleted  =   NOT_DELETED;
        if($media->save()){
            return $media;
        }
        return false;
    }

    public function updateProfilePic($id,$media_id){
        $user = User::findOne(['id'=>$id]);
        $profile = AppUserProfile::findOne(['id'=>$user->profile_id]);
        $profile->media_id =  $media_id;
        $profile->save(false);
    }

    public function getProfilePic(){
        if(!empty(Yii::$app->user->identity->profile->media)){
            return Yii::$app->user->identity->profile->media->file_url;
        }else{
            return Yii::$app->homeUrl."default_image/default_user.jpg";
        }
    }

    /*
     * @akshaysangani
     * user profile pic is default or not
     * use Account setting -> user view
     * */
    public function getUserProfilePic($id){

        $user = User::findUserByID($id);
        if(!empty($user->profile->media)){
            return $user->profile->media->file_url;
        }else{
            return Yii::$app->homeUrl."/default_image/default_user.jpg";
        }
    }

     //get browser info
    public function getBrowser() 
    { 
        $user_agent = $_SERVER['HTTP_USER_AGENT']; 
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

    //First get the platform?
        if (preg_match('/linux/i', $user_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $user_agent)) {
            $platform = 'windows';
        }

    // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$user_agent) && !preg_match('/Opera/i',$user_agent)) 
        { 
            $bname = 'Internet Explorer'; 
            $ub = "MSIE"; 
        } 
        elseif(preg_match('/Firefox/i',$user_agent)) 
        { 
            $bname = 'Mozilla Firefox'; 
            $ub = "Firefox"; 
        } 
        elseif(preg_match('/Chrome/i',$user_agent)) 
        { 
            $bname = 'Google Chrome'; 
            $ub = "Chrome"; 
        } 
        elseif(preg_match('/Safari/i',$user_agent)) 
        { 
            $bname = 'Apple Safari'; 
            $ub = "Safari"; 
        } 
        elseif(preg_match('/Opera/i',$user_agent)) 
        { 
            $bname = 'Opera'; 
            $ub = "Opera"; 
        } 
        elseif(preg_match('/Netscape/i',$user_agent)) 
        { 
            $bname = 'Netscape'; 
            $ub = "Netscape"; 
        } 

    // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $user_agent, $matches)) {
                // we have no matching number just continue
        }

            // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
            if (strripos($user_agent,"Version") < strripos($user_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

            // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $user_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
            );
    }
}