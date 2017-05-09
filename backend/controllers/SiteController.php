<?php
namespace backend\controllers;

use backend\models\Actions;
use backend\models\Controllers;
use backend\models\ResetPasswordForm;
use common\models\AppUserProfile;
use common\models\ForgotPasswordForm;
use common\models\form\SignupForm;
use common\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use common\models\Devices;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','forgot-password','reset','get-controllers-and-actions-sync'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','lock'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            /*'error' => [
                'class' => 'yii\web\ErrorAction',
            ],*/
        ];
    }

    public function actionError(){

        $this->layout ="error/main";
        $exception = Yii::$app->errorHandler->exception;
        return $this->render('error',['error'=>$exception]);

    }
    public function actionLogin() {
        $this->layout = "login/main";

        if (!Yii::$app->user->isGuest) {
            return $this->redirect(\Yii::$app->urlManager->createUrl("dashboard/index"));

        } else {
            $user = User::find()->one();
            if (empty($user)){
                return $this->redirect(Yii::$app->urlManager->createUrl('user/register'));
            }

            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->redirect(\Yii::$app->urlManager->createUrl("dashboard/index"));
            } else {
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
    }
    public function actionForgotPassword(){

        $this->layout = "login/main";
        $model = new ForgotPasswordForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if($model->load(Yii::$app->request->post())){
            $errors = ActiveForm::validate($model);
            if($errors){

            }else {
                if($model->sendResetEmail()){
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'Reset Password link has been sent to your email address',
                        'title' => 'Email Sent',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                }else{
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'danger',
                        'duration' => 3000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'Reset Password link has been sent to your email address',
                        'title' => 'Email not sent',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                }

                return $this->redirect(\Yii::$app->urlManager->createUrl("site/login"));
            }
        }
        return $this->render('forgot-password',[
            'model' => $model
        ]);
    }
    public function actionLock()
    {
        $this->layout = "login/main";

        $user = User::findIdentity(\Yii::$app->user->identity->id);
        $user->screenlock = ACTIVE;
        $user->save(false);

        $model = new LoginForm();
        $model->username = \Yii::$app->user->identity->username;
        if($model->load(Yii::$app->request->post()) && $model->unlock()){
            return $this->redirect(\Yii::$app->urlManager->createUrl("dashboard/index"));
        }
        return $this->render('lock',[
        'model' => $model,
            ]);

    }

    public function actionLogout()
    {
        if($this->unsetDeviceInfo()) 
            Yii::$app->user->logout();

        return $this->redirect(\Yii::$app->urlManager->createUrl("site/login"));
    }

    //to set information of device( web users) and set is_logged in property true
    public function setDeviceInfo(){      
     
       $device = new Devices;
       $device->user_id = Yii::$app->user->id;
       $device->device_platform = "web";
       $device->device_token = "web";
       $device->device_unique_id = "web";
       $device->is_login = LOGGED_IN;
       $device->access_token = Yii::$app->security->generateRandomString();
       $device->login_time = time();       
       $browser = Yii::$app->commonfunction->getBrowser(); 
       $device->os = $browser['platform'];
       $device->device_model = $browser['name'];//will give browser name
       $device->created_date = time();
       if($device->save()){
            //set accesstoken in session
            Yii::$app->session->set('access_token',$device->access_token);
            return true;
       }else{
            return false;
       }

    }

    //set is_logged_in to false
    public function unsetDeviceInfo(){    
        $access_token = Yii::$app->session->get('access_token');
        if(isset($access_token)){    
            $device = Devices::find()->where(['access_token'=>$access_token])->one();
            if($device){
                if($device->delete()){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }

        }else{
            return true;
        }
    }

    public function actionReset($token){
        $this->layout = "login/main";

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }
    public function actionGetControllersAndActionsSync(){

        if(Yii::$app->request->isAjax){
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $connection->createCommand()->checkIntegrity(false)->execute();
            $connection->createCommand('TRUNCATE controllers')->execute();
            $connection->createCommand('TRUNCATE actions')->execute();
            $connection->createCommand()->checkIntegrity(true)->execute();
            $get_controllers_actions = $this->getcontrollersandactions();
            try {
                $flag = 0;
                foreach ($get_controllers_actions as $controllers_name => $actions_name) {
                    $controller = new Controllers();
                    $controller->controller_name = $controllers_name;
                    $controller->slug = ucwords(str_replace('-', ' ', $controllers_name));
                    if ($controller->validate()) {
                        $controller->save();
                        $flag = 1;
                        foreach ($actions_name as $action_name) {
                            $action = new Actions();
                            $action->action_name = $action_name;
                            $action->controller_id = $controller->id;
                            $action->slug = ucwords(str_replace('-', ' ', $action_name));
                            if ($action->validate()) {
                                $action->save();
                                $flag = 1;
                            } else {
                                print_r($action->getErrors());
                                $flag = 0;
                            }
                        }
                    } else {
                        print_r($controller->getErrors());
                        $flag = 0;
                    }
                }
                if($flag == 1){
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 12000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'Controllers and Actions Sync Successfully',
                        'title' => 'Sync',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    echo "true";
                }else{
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'danger',
                        'duration' => 12000,
                        'icon' => 'icon-remove-sign',
                        'message' => 'Controllers and Actions not Sync',
                        'title' => 'Sync',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    echo "false";
                }
            }catch (Exception $e){
                print_r($e);
            }
        }
    }

    public function getcontrollersandactions()
    {
        $controllerlist = [];
        if ($handle = opendir('../controllers')) {
            echo "<pre>";
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = $file;
                }
            }
            closedir($handle);
        }
        asort($controllerlist);
        $fulllist = [];
        foreach ($controllerlist as $controller):
            $handle = fopen('../controllers/' . $controller, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (preg_match('/public function action(.*?)\(/', $line, $display)):
                        if (strlen($display[1]) > 2):
                            $fulllist[$this->changeCase(substr(str_replace('Controller','',$controller), 0, -4))][] = $this->changeCase($display[1]);
                        endif;
                    endif;
                }
            }
            fclose($handle);
        endforeach;
        return $fulllist;
    }

    protected function changeCase($input) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('-', $ret);
    }
}
