<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 2/8/16
 * Time: 11:49 AM
 */


namespace frontend\modules\webservices\controllers;

use common\models\ChangePasswordForm;
use frontend\components\RestController;
use Yii;
use yii\helpers\Json;
use common\models\User;
use frontend\models\Registration;
use common\models\Devices;
class UserController extends RestController
{

    public function actionIndex(){
        echo "rohan";
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionLogin()
    {
        try{
            $data="";
            if(Yii::$app->request->isPost){
                $postdata = JSON::decode(file_get_contents("php://input"));
                if(!empty($postdata['email']) && !empty($postdata['password'])){
                    $userData = User::findOne(['email'=>$postdata['email'],'status'=>ACTIVE,'is_delete'=>NOT_DELETED]);
                    if($userData){
                        if($userData->validatePassword($postdata['password'],$userData->password_hash)){
                            if($userData->status == USER_VERIFIED){
                                $device = $this->Login($userData,$postdata);
                                if($device){
                                    $code = 200;
                                    $data = $this->getProfile($userData,$device);
                                }
                            }else{
                                $code = $data['error']['code']=401;
                                $data['error']['message']=$this->getStatusCodeMessage($code);
                                $data['error']['description']= "This email address is not verified please verify ". $postdata['email'] .".";
                            }
                        }else{
                            $code = $data['error']['code']=401;
                            $data['error']['message']=$this->getStatusCodeMessage($code);
                            $data['error']['description']= "Password is wrong for ". $postdata['email'];
                        }
                    }else{
                        $code = $data['error']['code']=401;
                        $data['error']['message']=$this->getStatusCodeMessage($code);
                        $data['error']['description']= "email is not registered with this system.";
                    }
                }else{
                    $code = $data['error']['code'] = 400;
                    $data['error']['message']=$this->getStatusCodeMessage($code);
                }

            }else{
                $code = $data['error']['code']=405;
                $data['error']['message']=$this->getStatusCodeMessage($code);
            }
        } catch(\Exception $e){
            $code = $data['error']['code']=500;
            $data['error']['message']=$this->getStatusCodeMessage($code);
        }


        $this->sendResponse($code, JSON::encode($data));
    }
    public function login($user,$post){
        if($user){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            if(isset($post['deviceInfo'])) {
                $device = Devices::findOne(['device_unique_id'=>$post['deviceInfo']['device_unique_id']]);
                if($device){
                    $device->user_id = $user->id;
                    $device->device_platform = strtolower($post['deviceInfo']['device_platform']);
                    $device->device_token = $post['deviceInfo']['device_token'];
                    $device->device_unique_id = $post['deviceInfo']['device_unique_id'];
                    $device->device_model = $post['deviceInfo']['device_model'];
                    $device->access_token =  Yii::$app->security->generateRandomString();
                    $device->os = $post['deviceInfo']['os'];
                    $device->is_login = ACTIVE;
                    $device->login_time =time();

                    if(!$device->validate()){
                        $transaction->rollBack();
                        $this->errorResponder([],$device->getErrors());
                    }else{
                        $device->save();
                        $transaction->commit();
                        return $device;
                    }
                }else{
                    $device =  new Devices();
                    $device->user_id = $user->id;
                    $device->device_platform = strtolower($post['deviceInfo']['device_platform']);
                    $device->device_token = $post['deviceInfo']['device_token'];
                    $device->device_unique_id = $post['deviceInfo']['device_unique_id'];
                    $device->device_model = $post['deviceInfo']['device_model'];
                    $device->access_token =  Yii::$app->security->generateRandomString();
                    $device->os = $post['deviceInfo']['os'];
                    $device->is_login = ACTIVE;
                    $device->login_time = time();
                    $device->created_date= time();
                    if(!$device->validate()){
                        $transaction->rollBack();
                        $this->errorResponder([],$device->getErrors());
                    }else{
                        $device->save();
                        $transaction->commit();
                        return $device;
                    }
                }


            }

        }

    }

    public function actionChangePassword()
    {
        $data = "";
        $device = $this->checkAuth();
        $postdata = JSON::decode(file_get_contents("php://input"));
        //try {
            $model = new ChangePasswordForm();
            if ($postdata) {
                $model->attributes = $postdata;
                if ($model->resetPassword($device->user)) {


                    $user = User::findOne($device->user_id);
                    $user->password_changed = TRUE;
                    $user->save(false);
                    $profile = $this->getProfile($user, $device);
                    $code = 200;
                    $data = ['data' => $profile];
                }else{
                    $code = 400;
                    $data = $this->makeResponse($code,'not reseting');
                }

            }else{
                $code = 400;
                $data = $this->makeResponse($code,'wrong method use post instead');
            }
       /* } catch (\Exception $e) {
            $code = $data['error']['code'] = 500;
            $data['error']['message'] = $this->getStatusCodeMessage($code);
        }*/
        $this->sendResponse($code, JSON::encode($data));
    }

    public function actionProfile(){
        $device = $this->checkAuth();
        $profile = $this->getProfile($device->user,$device);

        $code = $data['code']=200;
        $data = ['data'=>$profile];
        $this->sendResponse($code, JSON::encode($data));
    }

    public function actionLogout(){
        $device = $this->checkAuth();
        $cd = Devices::findOne(['id'=>$device->id]);
        if($cd){
            $cd->access_token = NULL;
            $cd->is_login = INACTIVE;
            if($cd->save(false)){

                $code = $data['code']=200;
                $data['message'] = $this->getStatusCodeMessage($code);
                $data['description'] = "logout successfully";

            }else{
                $code = $data['error']['code']=500;
                $data['error']['message'] = $this->getStatusCodeMessage($code);
                $data['error']['description'] = "something went wrong!!";
            }

        }else{
            $code = $data['error']['code']=400;
            $data['error']['message'] = $this->getStatusCodeMessage($code);
            $data['error']['description'] = "user already logout";
        }
        $this->sendResponse($code, JSON::encode($data));

    }


    public function getProfile($user,$device) {

       // print_r($user->clientProfile);die;
        return  array(
            'first_name'=>isset($user->first_name)?$user->first_name:"",
            'last_name'=>isset($user->last_name)?$user->last_name:"",
            'full_name'=>isset($user->profile->full_name)?$user->profile->full_name:"",
            'email'=>isset($user->email)?$user->email:"",
            'profile_media'=>isset($user->profile->media->file_url)?$user->profile->media->file_url:"",
            'gender_name'=>isset($user->profile->gender)?User::getGenderName($user->profile->gender):"",
            'gender'=>isset($user->profile->gender)?$user->profile->gender:"",
            'client'=>isset($user->profile->client_id)?$user->profile->client->name :"",
            'date_of_birth'=>isset($user->profile->date_of_birth)?$user->profile->date_of_birth:"",
            'access_token'=>isset($device->access_token)?$device->access_token:"",
            'password_changed'=>isset($user->password_changed)?true:false,
        );

    }

      //forgot password
    public function actionForgotPassword()
    {
        try {
            $data = "";
            if (Yii::$app->request->isPost) {
                $postdata = JSON::decode(file_get_contents("php://input"));
                if (!empty($postdata['email'])) {
                    $userData = User::findOne(['email' => $postdata['email'],'status'=>ACTIVE]);

                    if ($userData) {
                        //Generate token
                        $userData->generatePasswordResetToken();
                        $userData->save(false);                        
                        //generate link and send mail
                        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset', 'token' => $userData->password_reset_token]);
                        $description = ['user_name'=>$userData->first_name." ".$userData->last_name,'reset_link'=>$resetLink];
                        $send = Yii::$app->email->send(FORGOT_PASSWORD_MAIL, $userData->email,ADMIN_EMAIL, $description); 
                        if($send){
                            $code = 200;
                            $data['code'] = $code;
                            $data['message'] = $this->getStatusCodeMessage($code);
                            $data['description'] = "Mail sent! Please check your inbox";
                        }else{
                           // echo "not sent";
                            $code = $data['error']['code'] = 422;
                            $data['error']['message'] = $this->getStatusCodeMessage($code);
                            $data['error']['description'] = "Mail not sent! Try again";
                        }
                       // DIE;

                    } else {
                        $code = $data['error']['code'] = 401;
                        $data['error']['message'] = $this->getStatusCodeMessage($code);
                        $data['error']['description'] = "User with this email does not exists" ;
                    }
                }else {
                    $code = $data['error']['code'] = 400;
                    $data['error']['message'] = $this->getStatusCodeMessage($code);
                }
            }else {
                $code = $data['error']['code'] = 405;
                $data['error']['message'] = $this->getStatusCodeMessage($code);
            }
        } catch (\Exception $e) {
            $code = $data['error']['code'] = 500;
            $data['error']['message'] = $this->getStatusCodeMessage($code);
        }


        $this->sendResponse($code, JSON::encode($data));
    }


}