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

    public function sendReservationEmail($content) {

        $result = $this->sendEmail('reservation',$content,"One New Request for Reservation");
        print_r($result);die;
    }
    public function sendTrialEmail() {
        $content = ["content"=>"rohan"];
        return $this->sendEmail('html',$content,"One New Request for Reservation");
       // print_r($result);die;
    }

    public function sendEmail($view,$content_array,$subject) {

        return \Yii::$app->mailer->compose($view, $content_array)
               ->setFrom(["noreply@thetatechnolabs.com" => 'Reservation Request'])
               ->setTo(ADMIN_EMAIL)
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

    //check user is reservationist
    public function isReservationist($id=""){
        $id = empty($id)?yii::$app->user->identity->getId():$id;
        if(!empty($id)){
            $user =  User::findOne(['id'=>$id]);
            if($user){

                if($user->role === RESERVATIONIST){
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
     public function isAccontant($id=""){
        $id = empty($id)?yii::$app->user->identity->getId():$id;
        if(!empty($id)){
            $user =  User::findOne(['id'=>$id]);
            if($user){

                if($user->role === ACCOUNTANT){
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
     public function isProcurement($id=""){
        $id = empty($id)?yii::$app->user->identity->getId():$id;
        if(!empty($id)){
            $user =  User::findOne(['id'=>$id]);
            if($user){

                if($user->role === PROCUREMENT){
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
    public function isClient($id=""){
        $id = empty($id)?yii::$app->user->identity->getId():$id;
        if(!empty($id)){
            $user =  User::findOne(['id'=>$id]);
            if($user){

                if($user->role === CLIENT){
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
    public function isClientAppUser($id=''){
        $id = empty($id)?yii::$app->user->identity->getId():$id;

        if(!empty($id)){
            $user = User::findOne(['id'=>$id]);
           // print_r($user);die;
            if($user){

                if($user->role === CLIENT_APP_USER){
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
    public function isStaffUser($id=''){
        $id = empty($id)?yii::$app->user->identity->getId():$id;
        if($this->isAdmin($id) || $this->isSuperAdmin($id) || $this->isProcurement($id) || $this->isReservationist($id) || $this->isAccontant($id)){
            return true;
        }else{
            return false;
        }
    }
    public function isClientUser($id=''){
        $id = empty($id)?yii::$app->user->identity->getId():$id;

        if($this->isClient($id) || $this->isClientAppUser($id)){
            return true;
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

        $uploadpath = Yii::getAlias('@backend').DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR;
        if(!is_dir($uploadpath)){
            mkdir($uploadpath,0777);
        }

        if(imagepng($img,$uploadpath.$imageName)){
          $media =  $this->uploadMedia($id,$uploadpath,$imageName);
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
        if($this->isSuperAdmin() || $this->isAdmin() || $this->isStaffUser()){
            $profile = StaffUserProfile::findOne(['id'=>$user->profile_id]);
        }elseif($this->isClientUser()){
            $profile = AppUserProfile::findOne(['id'=>$user->profile_id]);
        }
        $profile->media_id =  $media_id;
        $profile->save(false);
    }

    public function getProfilePic(){

        if(!empty(Yii::$app->user->identity->profile->media)){
            return Yii::$app->user->identity->profile->media->file_url;
        }else{
            return DEFAULT_IMAGE;
        }
    }
    public function createDateRangeArray($strDateFrom,$strDateTo) {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }
    /****
     *  Generate 'n' level array with modify key from 'n' level array
     * @type - Recursive Function.
     **/
    public function alterKeyInArray($array,$str) {
        $array = $this->removeString($array,$str);
        $keys = array_keys($array);
        foreach ($keys as $value) {
            if(is_array($array[$value])) {
                $array[$value]=$this->alterKeyInArray($array[$value],$str);
            }
        }
        return $array;
    }
    /****
     *  Support function for alterKeyIn Array
     *  intend to remove specific string from key
     **/
    public function removeString($arr,$str) {
        $resultArray = array();
        if(is_array($arr)){
            foreach ($arr as $key => $value) {
                $key=ltrim($key,$str);
                $resultArray[$key]=$value;
            }

        }
        return $resultArray;
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
    public function getBuyRates($res_id){
        $reservation = Reservations::findOne(['id'=>$res_id]);
        $total = $this->totalPayableToFac($reservation);
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        return money_format('%.2n',$total);
    }
    public function calculatePayment($res_id){
        $final = 0;
        $reservation = Reservations::findOne(['id'=>$res_id]);
        $total = $this->totalPayableToFac($reservation);
        $paid = $this->totalPaid($reservation);
        if($total === $paid){
            $final= 0;
        }else{
            $final =$total-$paid;
        }
        return "$ ".number_format(abs($final), 2, '.', '');
    }
    public function calculatePaymentforLog($res_id){
        $reservation = Reservations::findOne(['id'=>$res_id]);
        $total = $this->totalPayableToFac($reservation);
        $paid = $this->totalPaid($reservation);
        $final =$total-$paid;
        return number_format($final, 2, '.', '');
    }
    public function calculateInvoiceAmt($res_id){
        $reservation = Reservations::findOne(['id'=>$res_id]);
        $total = $this->totalInvoiceAmt($reservation);
        return "$ ".number_format($total, 2, '.', '');
    }

    public function totalPayableToFac($reservation){
        $rooms_rate=0;
        foreach($reservation->reservationRooms as $room){
            foreach($room->reservationRoomData as $rd){
                $roomrate = $rd->buy_rate;
                if($rd->tax_type){
                    $roomrate += $rd->tax;
                    $rooms_rate +=$roomrate;
                }else{
                    $roomrate *= (1 + $rd->tax / 100);
                    $rooms_rate +=$roomrate;
                }
            }

        }
        return $rooms_rate;

    }
    public function totalInvoiceAmt($reservation){
        $sell_rate=0;
        foreach($reservation->reservationRooms as $room){
            foreach($room->reservationRoomData as $rd){
                $roomrate = $rd->sell_rate;
                if($rd->tax_type){
                    $roomrate += $rd->tax;
                    $sell_rate +=$roomrate;
                }else{
                    $roomrate += ($rd->tax / 100) * $rd->buy_rate;
                    $sell_rate +=$roomrate;
                }
            }
        }
        return $sell_rate;

    }
    public function totalPaid($reservation){
        $amount=0;
        foreach($reservation->payment as $pay){
            $amount +=$pay->amount;
        }
        return $amount;

    }
    public function getTaxPerRoom($reservation){
        $taxrate = 0;
        foreach($reservation->reservationRooms as $room){
            foreach($room->reservationRoomData as $rd){

                if($rd->tax_type){
                    $taxrate += $rd->tax;
                }else{
                    $taxrate += ($rd->tax / 100) * $rd->buy_rate;

                }
            }
        }
        return $taxrate;

    }
    public function authrisedAmt($reservation,$night=1){
        foreach($reservation->reservationRooms as $room){
            foreach($room->reservationRoomData as $rd){
                return "$ ".number_format($rd->auth, 2, '.', '');
            }
        }

    }
    public function cardNumber($reservation,$night=1){
        foreach($reservation->reservationRooms as $room){
            foreach($room->reservationRoomData as $rd){
                $card = str_replace(' ', '', $rd->cc_no);
                return 'XXXXXX'.substr($card, -10);

            }
        }
    }
    public function expiry($reservation,$night=1){
        foreach($reservation->reservationRooms as $room){
            foreach($room->reservationRoomData as $rd){
                return $rd->expiry;

            }
        }
    }
    public function cvc($reservation,$night=1){
        foreach($reservation->reservationRooms as $room){
            foreach($room->reservationRoomData as $rd){
                return $rd->cvc;

            }
        }
    }

    public function getTotalPayable($dataprovider){
        $data =0;
        foreach($dataprovider->getModels() as $model){
            $data +=$this->totalPayableToFac($model);
        }

        return $data;
    }
    public function getTotalInvoice($dataprovider){
        $data =0;
        foreach($dataprovider->getModels() as $model){
            $data +=$this->totalInvoiceAmt($model);
        }

        return $data;
    }
    public function getGP($buy,$sell){
        $gp = $sell-$buy;
        return "$ ".number_format($gp, 2, '.', '');
    }
    public function getDoubleGuestName($reservation){
        $string = "";
        foreach($reservation->reservationRooms as $room){
            if($room->room_type == ReservationRooms::DOUBLEROOM){
                foreach($room->reservationGuests as $guest){
                    $string .= $guest->person->first_name." ".$guest->person->last_name.',';
                }
            }
        }
        return $string;
    }
    public function getSingleGuestName($reservation){
        $string = "";
        foreach($reservation->reservationRooms as $room){
            if($room->room_type == ReservationRooms::SINGLEROOM){
                foreach($room->reservationGuests as $guest){
                    $string .= $guest->person->first_name." ".$guest->person->last_name.',';
                }
            }
        }
        return $string;
    }
    public function sell_rate($roomdata){
        $sell_rate = $roomdata->sell_rate;
        $sell_rate += ($roomdata->tax / 100) * $roomdata->buy_rate;
        return $sell_rate;
    }

    public function totalSingle($data){
        $result =0;
        foreach($data as $d){
            $result +=$d['single'];
        }
        return $result;
    }
    public function totalDouble($data){
        $result =0;
        foreach($data as $d){
            $result +=$d['double'];
        }
        return $result;
    }
    public function totalAmt($data){
        $result =0;
        foreach($data as $d){
            $result +=$d['total'];
        }
        return $result;
    }
    public function totalAllSingle($datas){
        $result =0;
        foreach($datas as $data){
            foreach($data as $d){
                $result +=$d['single'];
            }
        }
        return $result;
    }
    public function totalAllDouble($datas){
        $result =0;
        foreach($datas as $data){
            foreach($data as $d){
                $result +=$d['double'];
            }
        }
        return $result;
    }
    public function totalAllAmt($datas){
        $result =0;
        foreach($datas as $data){
            foreach($data as $d){
                $result +=$d['total'];
            }
        }
        return $result;
    }
    public function getGuestName($room){
        $person = [];
       foreach($room->reservationRooms->reservationGuests as $r){
           array_push($person,$r->person->first_name.' '.$r->person->last_name);
       }
        return implode(', ',$person);
    }
    public function getTotalRooms($data){
        $count =0;
        foreach($data as $d){
            foreach($d as $r){
                $count++;
            }
        }
        return $count;
    }
    public function changeStatusForAll($reservation,$status){
            return ReservationRooms::updateAll(['status'=>$status],'reservation_id='.$reservation->id);
    }
    public function getTotalRoomsAmt($data){
        $count =0;
        foreach($data as $d){
            foreach($d as $r){
                $count+=$this->sell_rate($r);
            }
        }
        return $count;
    }
    public function getRoomsRequestStatus($reservation,$room){
        foreach($reservation->reservationExtendRequest as $request){
            if($request->reservation_room_id  ==  $room->id){
                if($request->status == 1){
                    return true;
                }else if($request->status == 2 ){
                    return true;
                }else{
                    return false;
                }
            }
        }
        return true;
    }
    public function getExtendString($request,$model){
        $string ='';
        if($request->extend_type == 1){

            if(isset($request->single_room) && !empty($request->single_room)){
                $string .= $request->single_room.' Single room ';
            }else if(isset($request->double_room) && !empty($request->double_room)){
                $string .= $request->double_room.' Double room ';
            }
            $occupant = Json::decode($request->occupan_info);
            $guests = [];
            if(isset($occupant['single_room'])) {
                foreach ($occupant['single_room'] as $persons) {
                    foreach ($persons as $person) {
                        array_push($guests, $person['first_name'] . ' ' . $person['last_name']);
                    }
                }
            }
            if(isset($occupant['double_room'])){
                foreach($occupant['double_room'] as $persons){
                    foreach($persons as $person){
                        array_push($guests, $person['first_name'].' '.$person['last_name']);
                    }
                }
            }

            $string .=" added with new check in date ".date('m/d/Y',strtotime($request->check_in_date))." & new check out date ".date('m/d/Y',strtotime($request->check_out_date));
            $string.=" with Guest name: ".implode(' ,',$guests);



        }else{
            if ($request->check_in_date == $model->check_in_date && $request->check_out_date > $model->check_out_date) {
                //extend date after check out date
                $guests = [];
                foreach($request->requestRoom->reservationGuests as $guest){
                    array_push($guests,$guest->person->first_name." ".$guest->person->last_name);
                }
                $string = ($request->requestRoom->room_type == ReservationRooms::DOUBLEROOM)?'Double ':'Single ';
                $string.="Room's Check out date is updated to ".date('m/d/Y',strtotime($request->check_out_date));
                $string.=" with Guest name: ".implode(' ,',$guests);
            } else if ($request->check_in_date == $model->check_in_date && $request->check_out_date < $model->check_out_date) {
                //early check out
                $guests = [];
                foreach($request->requestRoom->reservationGuests as $guest){
                    array_push($guests,$guest->person->first_name." ".$guest->person->last_name);
                }
                $string = ($request->requestRoom->room_type == ReservationRooms::DOUBLEROOM)?'Double':'Single';
                $string .= " Room's Check out date is updated to ".date('m/d/Y',strtotime($request->check_out_date));
                $string .= " with Guest name: ".implode(' ,',$guests);
            } else if ($request->check_in_date < $model->check_in_date && $request->check_out_date == $model->check_out_date) {
                $guests = [];
                foreach($request->requestRoom->reservationGuests as $guest){
                    array_push($guests,$guest->person->first_name." ".$guest->person->last_name);
                }
                $string = ($request->requestRoom->room_type == ReservationRooms::DOUBLEROOM)?'Double':'Single';
                $string.="Room's Check in date is updated to ".date('m/d/Y',strtotime($request->check_in_date));
                $string .= " with Guest name: ".implode(' ,',$guests);
                // early Check in.
            } else if ($request->check_in_date > $model->check_in_date && $request->check_out_date == $model->check_out_date) {
                $guests = [];
                foreach($request->requestRoom->reservationGuests as $guest){
                    array_push($guests,$guest->person->first_name." ".$guest->person->last_name);
                }
                //check in date updated.
                $string = ($request->requestRoom->room_type == ReservationRooms::DOUBLEROOM)?'Double':'Single';
                $string.="Room's Check in date is updated to ".date('m/d/Y',strtotime($request->check_in_date));
                $string.=" with Guest name: ".implode(' ,',$guests);

            }else{
                $guests = [];
                foreach($request->requestRoom->reservationGuests as $guest){
                    array_push($guests,$guest->person->first_name." ".$guest->person->last_name);
                }

                $string = ($request->requestRoom->room_type == ReservationRooms::DOUBLEROOM)?'Double ':'Single ';
                $string .= "Room's check in date & check out date is extended.new dates are( ".date('m/d/Y',strtotime($request->check_in_date))." - ".date('m/d/Y',strtotime($request->check_out_date))." )";
                $string.=" with Guest name: ".implode(' ,',$guests);

            }

        }
        return $string;
    }
    public function check_in_class($room){
         if($room->status == \common\models\Reservations::CHECKED_IN ||$room->status == \common\models\Reservations::CHECK_OUT){
             return "checked-in btn-primary";
         }elseif($room->check_in_date ==  date('Y-m-d')){
            return "check-in btn-warning";
         }else{
             return "check-in btn-info";
         }
    }
    public function check_in_button($room){
        if($room->status == \common\models\Reservations::CHECKED_IN ||$room->status == \common\models\Reservations::CHECK_OUT){
            return "Checked In";
        }elseif($room->check_in_date ==  date('Y-m-d')){
            return "Check In Tonight";
        }else{
            return "Check In";
        }
    }

}