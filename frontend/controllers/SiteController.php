<?php
namespace frontend\controllers;


use common\models\Devices;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

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
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup','terms-and-conditions'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
      if(Yii::$app->user->isGuest){
          return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('site/login'));
      }else{
          return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('client/choose-services'));
      }
    }
    public function actionTermsAndConditions()
    {
        return $this->render('term-condition');
    }
    public function actionError(){
        $this->layout ="error";
        $exception = Yii::$app->errorHandler->exception;
        return $this->render('error',['error'=>$exception]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->setDeviceInfo();
            return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('client/choose-services'));
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    public function setDeviceInfo(){

        $device = new Devices();
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
    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        $this->unsetDeviceInfo();
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
