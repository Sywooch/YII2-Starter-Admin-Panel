<?php
namespace backend\controllers;
use backend\components\CustController;
use common\models\ChangePasswordForm;
use common\models\form\SignupForm;
use common\models\StaffUserProfile;
use common\models\AppUserProfile;
use common\models\form\StaffUserProfileForm;
use common\models\form\AppUserProfileForm;
use common\models\User;
use Yii;
use yii\web\Controller;
use yii\web\Response;
//use yii\web\User;
use yii\widgets\ActiveForm;
use kartik\growl\Growl;
use yii\helpers\Html;
/**
 * User controller
 */
class UserController extends CustController
{

    public function actionRegister(){

        $this->layout = "login/main";
        $model = new SignupForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $profile = new AppUserProfile();
            $profile->first_name = $model->first_name;
            $profile->last_name = $model->last_name;
            $profile->full_name = $model->first_name . " " . $model->last_name;
            $profile->email = $model->email;
            $profile->is_deleted = NOT_DELETED;
            $profile->created_by = SUPER_ADMIN;
            $profile->updated_by = SUPER_ADMIN;
            $profile->is_app_user = 1;
            $profile->status = ACTIVE;
            $profile->created_date = time();
            $profile->updated_date = time();

            if ($profile->validate()) {
                $profile->save();

                $user = new User();
                $user->username = $model->email;
                $user->first_name = $model->first_name;
                $user->last_name = $model->last_name;
                $user->email = $model->email;
                $user->generateAuthKey();
                $user->setPassword($model->password);
                $user->profile_id = $profile->id;
                $user->password_reset_token = $user->generatePasswordResetToken();
                $user->role_id = SUPER_ADMIN;
                $user->screenlock = 0;
                $user->status = ACTIVE;
                $user->created_at = time();
                $user->updated_at = time();

                if ($user->validate()) {
                    $user->save();
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 12000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'Your Account Register Successfully',
                        'title' => 'Signup',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    return $this->redirect(['site/login']);
                } else {
                    $transaction->rollBack();
                }
            } else {
                $transaction->rollBack();
            }
        } else {
            return $this->render('register', [
                'model' => $model,
            ]);
        }
    }

    public function actionProfile()
    {
        return $this->render('profile');
    }

    public function actionEditProfile(){
       if(Yii::$app->commonfunction->isStaffUser()){

          $model =  new StaffUserProfileForm();
          $user = User::findOne(['id' => yii::$app->user->identity->getId()]);          
          $model->first_name= $user->first_name;
          $model->last_name= $user->last_name;  
          $model->email= $user->email;  
          $model->phone_no= $user->profile->phone_no;  
          $model->gender= $user->profile->gender; 
          $model->date_of_birth=$user->profile->date_of_birth; 
          $model->address=$user->profile->address;
          $model->user_id= yii::$app->user->identity->getId();
          $model->media_id = $user->profile->media_id;
          if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }  
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();            
            $user->attributes = $model->attributes;
            $user->first_name = $model->first_name;
            $user->last_name = $model->last_name;
            $user->email = $model->email;          
            $user->updated_at = time();                              
            if ($user->save()) {
                $profile = StaffUserProfile::findOne(['id' => $user->profile_id]);
                $profile->scenario = 'normal';
                $profile->first_name = $model->first_name;
                $profile->last_name = $model->last_name;                  
                $profile->full_name = $model->first_name." ".$model->last_name;
                $profile->phone_no = $model->phone_no;
                $profile->gender = $model->gender;
                $profile->address = $model->address;
                $profile->date_of_birth = $model->date_of_birth;  
                $profile->media_id = isset($_POST['logo']) ? $_POST['logo'] : $model->media_id;                 
                if ($profile->save()) {                                     
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 12000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'Profile updated successfully',
                        'title' => 'Profile updated',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);                           
                } else {
                 $transaction->rollBack();
                 Yii::$app->getSession()->setFlash('danger', [
                    'type' => 'danger',
                    'duration' => 12000,
                    'icon' => 'glyphicon glyphicons-remove',
                    'message' => 'Something went wrong',
                    'title' => 'Error',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);
                 $transaction->rollBack();
             }               
         }else{
             $transaction->rollBack();     
             Yii::$app->getSession()->setFlash('danger', [
                'type' => 'danger',
                'duration' => 12000,
                'icon' => 'glyphicon glyphicons-remove',
                'message' => 'Something went wrong',
                'title' => 'Error',
                'positonY' => 'top',
                'positonX' => 'right'
                ]);
         }            
         return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('user/profile'));    

     }          
     return $this->render("edit_staff_user",['model'=>$model]);
    }else{
      $model =  new AppUserProfileForm();       
      $user = User::findOne(['id' => yii::$app->user->identity->getId()]);          
      $model->first_name= $user->first_name;
      $model->last_name= $user->last_name;  
      $model->email= $user->email;  
      $model->phone_no= $user->profile->phone_no;  
      $model->gender= $user->profile->gender; 
      $model->date_of_birth=$user->profile->date_of_birth; 
      $model->address=$user->profile->address;
      $model->user_id= yii::$app->user->identity->getId();
      $model->media_id = $user->profile->media_id;
      if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    } 
    if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {       
           $connection = \Yii::$app->db;
           $transaction = $connection->beginTransaction();            
           $user->attributes = $model->attributes;
           $user->first_name = $model->first_name;
           $user->last_name = $model->last_name;
           $user->email = $model->email;          
           $user->updated_at = time();                              
           if ($user->save()) {
            $profile = AppUserProfile::findOne(['id' => $user->profile_id]);
            $profile->first_name = $model->first_name;
            $profile->last_name = $model->last_name;                  
            $profile->full_name = $model->first_name." ".$model->last_name;
            $profile->phone_no = $model->phone_no;
            $profile->gender = $model->gender;
            $profile->address = $model->address;
            $profile->date_of_birth = $model->date_of_birth;  
            $profile->media_id = isset($_POST['logo']) ? $_POST['logo'] : $model->media_id;                 
            if ($profile->save()) {                                               
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 12000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'Profile updated successfully',
                        'title' => 'Profile updated',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);                           
                } else {
                 $transaction->rollBack();
                 print_r($profile->getErrors());
                 die;
                 Yii::$app->getSession()->setFlash('danger', [
                    'type' => 'danger',
                    'duration' => 12000,
                    'icon' => 'glyphicon glyphicons-remove',
                    'message' => 'Something went wrong',
                    'title' => 'Error',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);
                 $transaction->rollBack();
             }               
         }else{
             $transaction->rollBack();     
             Yii::$app->getSession()->setFlash('danger', [
                'type' => 'danger',
                'duration' => 12000,
                'icon' => 'glyphicon glyphicons-remove',
                'message' => 'Something went wrong',
                'title' => 'Error',
                'positonY' => 'top',
                'positonX' => 'right'
                ]);
         }            
         return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('user/profile'));    

     } 
     echo $this->render("edit_client_user",['model'=>$model]);
    }       

}


public function actionChangePassword(){
    $model = new ChangePasswordForm();
    if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }
    if(Yii::$app->request->post() && $model->load(Yii::$app->request->post())){
        $errors = ActiveForm::validate($model);
        if($errors){
            return $errors;
        }else{
            if($model->resetPassword()){
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 12000,
                    'icon' => 'glyphicon glyphicon-ok-sign',
                    'message' => 'You have changed your password successfully',
                    'title' => 'Password Changed',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);
                return $this->redirect(\Yii::$app->urlManager->createUrl("user/change-password"));
            }

        }
    }

    return $this->render('changepassword',['model'=>$model]);
}
public function actionToggleStatus(){
    if(Yii::$app->request->isAjax){
        $user =\common\models\User::findOne(['id'=>Yii::$app->user->identity->id]);
        if($user->is_available){
            $user->is_available = INACTIVE;
            $user->save(false);
            echo '<i class="fa fa-times red" style="font-size: 25px;"></i> Not Available';
        }else{
            $user->is_available = ACTIVE;
            $user->save(false);
            echo '<i class="fa fa-check green" style="font-size: 25px;"></i> Available';

        }

    }else{
        echo false;
    }
}

}
