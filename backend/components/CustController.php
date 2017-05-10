<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 10/6/16
 * Time: 7:14 PM
 */
namespace backend\components;

use common\models\Actions;
use common\models\Controllers;
use common\models\Rights;
use common\models\User;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class CustController extends Controller
{

    public $record;

    public function beforeAction($action)
    {

        $data = $this->getControllersActionsID($action);
        if (!parent::beforeAction($action)) {
            return false;
        }
        if (Yii::$app->user->isGuest) {
            $user = User::find()->one();
            if (!empty($user)){
                $this->redirect(\Yii::$app->urlManager->createUrl("site/login"));
                return false; //not run the action
            }

        } else {
            if (Yii::$app->commonfunction->isLocked()) {
                $this->redirect(\Yii::$app->urlManager->createUrl("site/lock"));
            } else {
                if ($this->checkRights($data)) {
                    return true;
                } else {
                     if(!DEV){
                         //redirect to 403 forbidden
                       // throw new ForbiddenHttpException();
                    }else{
                        return true;
                    }
                }
            }
        }

        return true; // continue to run action
    }

    public function afterAction($action, $result)
    {
        if (!parent::afterAction($action, $result)) {
            return false;
        }
        $controller = Controllers::find()->where(['controller_name' => $action->controller->id])->one();

        if ($controller) {
            $action = Actions::find()->where(['action_name' => $action->id, 'controller_id' => $controller->id])->one();
            if ($action) {
             //   Yii::$app->userActivity->add($this->record, $action->activity_value);

            }
        }
        return $result;

    }
    /**
     * Get Controller Id & Action Id  From Table
     * @param object of yii/base/Action
     * @return array
     */
    public function getControllersActionsID($action)
    {
        $controller = Controllers::find()->where(['controller_name' => $action->controller->id])->one();
        if ($controller) {
            $controllerID = $controller->id;
            $actions = Actions::find()->where(['action_name' => $action->id, 'controller_id' => $controller->id])->one();
            if ($actions) {
                $actionID = $actions->id;
            }
        }
        $data['controllerID'] = isset($controllerID) ? $controllerID : "";
        $data['actionID'] = isset($actionID) ? $actionID : "";

        return $data;
    }

    /**
     * Check User have right for particular action
     * @param Array
     * @return boolean
     * @author Disha P.
     */
    public function checkRights($data)
    {
        if (!empty($data['controllerID']) && !empty($data['actionID'])) {
            $rights = Rights::find()->where(['controller_id' => $data['controllerID'], 'action_id' => $data['actionID'], 'role_id' => Yii::$app->user->identity->role])->one();
            if ($rights) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}