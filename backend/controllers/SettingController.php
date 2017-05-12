<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 10/5/17
 * Time: 11:30 PM
 */

namespace backend\controllers;
use backend\components\CustController;
use backend\models\Actions;
use backend\models\ActionSearch;
use backend\models\Controllers;
use backend\models\ControllerSearch;
use backend\models\ChangeUserPasswordForm;
use backend\models\Menus;
use backend\models\MenusSearch;
use backend\models\Rights;
use common\models\Role;
use common\models\form\SystemUserProfileForm;
use common\models\search\RoleSearch;
use common\models\search\UserSearch;
use common\models\AppUserProfile;

use kartik\form\ActiveForm;
use Yii;
use common\models\User;
use yii\web\Response;

/**
 * Setting controller
 */
class SettingController extends CustController
{

    /***
     * @Akshaysanagni
     *  List of User(System User)
     *  Who user Admin Panel
     **/
    public function actionIndex() {

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=PAGESIZE;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /***
     * @AkshaySangani
     *  Add User(System User)
     *  Who user Admin Panel
     **/
    public function actionAddUser() {
        $model = new SystemUserProfileForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();
            $profile = new AppUserProfile();
            $profile->first_name = $model->first_name;
            $profile->last_name = $model->last_name;
            $profile->full_name = $model->first_name . " " . $model->last_name;
            $profile->email = $model->email;
            $profile->date_of_birth = $model->date_of_birth;
            $profile->phone_no = $model->phone_no;
            $profile->gender = $model->gender;
            $profile->is_deleted = NOT_DELETED;
            $profile->created_by = Yii::$app->user->identity->id;
            $profile->updated_by = Yii::$app->user->identity->id;
            $profile->is_app_user = 1;
            $profile->status = ACTIVE;
            $profile->created_date = time();
            $profile->updated_date = time();

            if($profile->validate()){
                $profile->save();

                $user = new User();
                $user->username = $model->email;
                $user->first_name = $model->first_name;
                $user->last_name = $model->last_name;
                $user->email = $model->email;
                $user->generateAuthKey();
                $user->setPassword(DEFAULT_PASSWORD);
                $user->profile_id = $profile->id;
                $user->password_reset_token = $user->generatePasswordResetToken();
                $user->role_id = $model->role;
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
                        'message' => 'You have created user & user successfully',
                        'title' => 'User Created',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                } else {
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
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('setting/index'));
            } else {
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
        } else {
            return $this->render("add_system_user",[
                'model'=>$model
            ]);
        }
    }

    /***
     * @akshaysangani
     *  Edit User(System User)
     **/
    public function actionEditUser($id)
    {
        $model = new SystemUserProfileForm();
        $user = User::findIdentity($id);
        $model->first_name = $user->first_name;
        $model->last_name = $user->last_name;
        $model->email = $user->email;
        $model->phone_no = $user->profile->phone_no;
        $model->gender = $user->profile->gender;
        $model->date_of_birth = $user->profile->date_of_birth;
        $model->role = $user->role->id;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();
            $profile = AppUserProfile::find()->where(['id' => $user->profile_id])->one();
            $profile->attributes = $model->attributes;
            $profile->full_name = $model->first_name . " " . $model->last_name;
            $profile->date_of_birth = $model->date_of_birth;
            $profile->updated_by = Yii::$app->user->identity->id;
            $profile->updated_date = time();

            if ($profile->validate()) {
                $profile->save(false);

                $user->attributes = $model->attributes;
                $user->role_id = $model->role;
                $user->updated_at = time();

                if ($user->validate()) {
                    $user->save(false);
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 12000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'User updated successfully',
                        'title' => 'User updated',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);

                    return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('setting/index'));
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
        }

        return $this->render("edit_system_user", [
            'model' => $model
        ]);
    }

    public function actionViewUser($id){
        return $this->render('view_user', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id){
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /***
     * @akshaysangani
     *  Change change Password of User(System User)
     *  Who user Admin Panel
     **/
    public function actionChangeUserPassword($id){
        $model = new ChangeUserPasswordForm();
        $model->user_id = $id;
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if(Yii::$app->request->post() && $model->load(Yii::$app->request->post())){
            $errors = ActiveForm::validate($model);
            if($errors){

            }else{
               // print_r($model->attributes);die;
                if($model->resetPassword()){
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 12000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'You have changed user password successfully',
                        'title' => 'Password Changed',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    return $this->redirect(\Yii::$app->urlManager->createUrl(["setting/view-user/",'id'=>$id]));
                }

            }
        }
        return $this->render('change_user_password',['model'=>$model]);
    }

    /***
     * @akshaysangani
     *  Change Status of User(System User)
     *  Who user Admin Panel
     **/
    public function actionStatusUser($id) {
        $model = User::findUserByID($id);
        if($model->status){
            $model->status =  INACTIVE;
        }else{
            $model->status =  ACTIVE;
        }
        echo $model->save(false);

    }

    /***
     * @akshaysangani
     *  Lists Roles(System User)
     *  All Roles for system
     **/
    public function actionAddRole() {
        $model = new Role();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if(Yii::$app->request->post() && $model->load(Yii::$app->request->post())){
            $model->is_deleted = NOT_DELETED;
            $model->created_by = Yii::$app->user->identity->getId();
            $model->updated_by = Yii::$app->user->identity->getId();
            $model->updated_at = time();
            $model->created_at = time();
            $errors = ActiveForm::validate($model);
            if($errors){
                return $errors;
            }else{
                if($model->save()){
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'You have created role successfully',
                        'title' => 'Roless Created',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    return $this->redirect(\Yii::$app->urlManager->createUrl("setting/roles"));
                }

            }
        }

        return $this->render("addroles",[
            'model'=>$model
        ]);
    }
    /***
     * @akshaysangani
     *  Lists Roles(System User)
     *  All Roles for system
     **/
    public function actionEditRole($id)  {
        $model = Role::findOne(['id'=>$id]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if(Yii::$app->request->post() && $model->load(Yii::$app->request->post())){
            $model->is_deleted = NOT_DELETED;
            $model->updated_by = Yii::$app->user->identity->getId();
            $model->updated_at = time();
            $errors = ActiveForm::validate($model);
            if($errors){
                return $errors;
            }else{
                if($model->save()){
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'You have updated role successfully',
                        'title' => 'Role Updated',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    return $this->redirect(\Yii::$app->urlManager->createUrl("setting/roles"));
                }

            }
        }

        return $this->render("addroles",[
            'model'=>$model
        ]);
    }
    /***
     * @akshaysangani
     *  List Roles(System User)
     *   All Roles for system
     **/
    public function actionRoles()  {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=PAGESIZE;
        return $this->render('roles', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /***
     * @akshaysangani
     *  Add Roles(System User)
     *  Who user Admin Panel
     **/
    public function actionStatusRole($id) {
        $model = Role::findOne(['id'=>$id]);
        if ($model->status) {
            $model->status = INACTIVE;
        } else {
            $model->status = ACTIVE;
        }
        echo $model->save(false);
    }

    /***
     * @rohanmashiyava
     *  List Menus(System User)
     *  Who user Admin Panel
     **/
    public function actionMenus() {
        $searchModel = new MenusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('menus', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
/**
 * @rohanmashiayava
 * add Menus
 **/
    public function actionAddMenu(){
        $model = new Menus();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if(Yii::$app->request->post() && $model->load(Yii::$app->request->post())){
            $cnt = Menus::find()->select('MAX(`order`)')->scalar();
            $model->order = $cnt+1;
            $errors = ActiveForm::validate($model);
            if($errors){
                return $errors;
            }else{
                if($model->save()){
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'You have created Menu successfully',
                        'title' => 'Menus Created',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    return $this->redirect(\Yii::$app->urlManager->createUrl("setting/menus"));
                }

            }
        }

        return $this->render("addmenus",[
            'model'=>$model
        ]);
    }
    /**
     * @rohanmashiayava
     * edit Menu
     **/
    public function actionEditMenu($id) {
        $model = Menus::findOne(['id'=>$id]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
            $errors = ActiveForm::validate($model);
            if ($errors){
                return $errors;
            } else {
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'You have updated Menu successfully',
                        'title' => 'Menus Updated',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    return $this->redirect(\Yii::$app->urlManager->createUrl("setting/menus"));
                }

            }
        }
        return $this->render("editmenus",[
            'model'=>$model
        ]);
    }

    /**
     * @rohanmashiayava
     * dependant dropdown ajax function
     **/

    public function actionSetAction($id) {
        if (Yii::$app->request->isAjax) {
            $model = Controllers::findOne(['id'=>$id]);
            $modelcount = sizeof($model->actions);
            if($modelcount>0){
                foreach($model->actions as $action) {
                    echo "<option value='".$action->id."'>".$action->action_name."</option>";
                }
            } else {
                echo "<option>-</option>";
            }

        }
        return false;
    }
    /**
     * @rohanmashiayava
     * change Order of Menu - Up
     **/
    public function actionUp($id) {
        $model = Menus::findOne(['id'=>$id]);
        if($model){
            $recordorder  = $model->order;
            if($recordorder == 1) {
                return false;
            } else {
                $target_order = $recordorder-1;
                $current = Menus::findOne(['order'=>$recordorder]);
                $target = Menus::findOne(['order'=>$target_order]);
                $current->order = $target_order;
                $target->order = $recordorder;
                $current->save(false);
                $target->save(false);
                return true;
            }
        }
    }

    /**
     * @rohanmashiayava
     * change Order of Menu - Down
     **/
    public function actionDown($id) {
        $model = Menus::findOne(['id'=>$id]);
        if($model){
            $recordorder  = $model->order;
            $max =  Menus::find() // AQ instance
                ->select('MAX(`order`)') // we need only one column
                ->scalar();
            if($recordorder == $max){
                return false;
            } else {
                $target_order = $recordorder+1;
                $current = Menus::findOne(['order'=>$recordorder]);
                $target = Menus::findOne(['order'=>$target_order]);
                $current->order = $target_order;
                $target->order = $recordorder;
                $current->save(false);
                $target->save(false);
                return true;
            }
        }
    }

    /**
     * @rohanmashiayava
     *  Menu - Delete
     **/

    public function actionDeleteMenu($id) {
        $model = Menus::findOne(['id'=>$id]);
        $max =  Menus::find()->select('MAX(`order`)')->scalar();

        for($i=$model->order;$i<=$max;$i++){
            //$model = Menus::findOne(['id'=>$])
            if($i == $model->order) {
                $delete = Menus::findOne(['order'=>$i]);
                $delete->delete();

            } else {
                $update = Menus::findOne(['order'=>$i]);
                $update->order = $update->order-1;
                $update->save(false);
            }

        }
        return true;
    }

    /**
     * @rohanmashiayava
     *  Controller - index - View
     **/

    public function actionControllers() {
        $searchModel = new ControllerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=PAGESIZE;

        return $this->render('controllers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * @rohanmashiayava
     *  Controller - Add
     **/
    public function actionAddController() {
        $model = new Controllers();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
            $errors = ActiveForm::validate($model);
            if ($errors){
                return $errors;
            } else {
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'You have Added Controller successfully',
                        'title' => 'Controller Added',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    return $this->redirect(\Yii::$app->urlManager->createUrl("setting/controllers"));
                }

            }
        }
        return $this->render("addcontroller",[
            'model'=>$model
        ]);
    }
    /**
     * @rohanmashiayava
     *  Controller - Edit
     **/
    public function actionEditController($id) {
        $model = Controllers::findOne(['id'=>$id]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
            $errors = ActiveForm::validate($model);
            if ($errors){
                return $errors;
            } else {
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'You have updated Controller successfully',
                        'title' => 'Controller Updated',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    return $this->redirect(\Yii::$app->urlManager->createUrl("setting/controllers"));
                }

            }
        }
        return $this->render("editcontroller",[
            'model'=>$model
        ]);
    }
    /**
     * @rohanmashiayava
     *  Controller - Delete
     **/

    public function actionDeleteController($id) {

        $model = Controllers::findOne(['id'=>$id]);
        if($model){
            $model->delete();
            return true;
        }else{
            return false;
        }

    }
    /**
     * @rohanmashiayava
     *  Action - index - View
     **/

    public function actionActions() {
        $searchModel = new ActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=PAGESIZE;

        return $this->render('actions', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * @rohanmashiayava
     *  Action - Add
     **/
    public function actionAddAction() {
        $model = new Actions();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
            $errors = ActiveForm::validate($model);
            if ($errors){
                return $errors;
            } else {
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'You have Added Action successfully',
                        'title' => 'Action Added',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    return $this->redirect(\Yii::$app->urlManager->createUrl("setting/actions"));
                }

            }
        }
        return $this->render("addaction",[
            'model'=>$model
        ]);
    }
    /**
     * @rohanmashiayava
     *  Action - Edit
     **/
    public function actionEditAction($id) {
        $model = Actions::findOne(['id'=>$id]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
            $errors = ActiveForm::validate($model);
            if ($errors){
                return $errors;
            } else {
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'You have updated Action successfully',
                        'title' => 'Action Updated',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    return $this->redirect(\Yii::$app->urlManager->createUrl("setting/actions"));
                }

            }
        }
        return $this->render("editaction",[
            'model'=>$model
        ]);
    }
    /**
     * @rohanmashiayava
     *  Actions - Delete
     **/

    public function actionDeleteAction($id) {

        $model = Actions::findOne(['id'=>$id]);
        if($model){
            $model->delete();
            return true;
        }else{
            return false;
        }

    }

    /**
     * @rohanmashiayava
     *  Right management
     **/
    public function actionRights($id = "") {
        $rights = new Rights();
        $flag = false;
        $controllers = Controllers::find()->all();
        $roleData = [];
        $moduleData = [];
        if(!empty($id)){
            $roleData = Rights::findAll(['role_id'=>$id]);
            foreach($controllers as $controller){
                foreach($controller->actions as $action){
                        $moduleData[$controller->id][] = $action->id;
                }
            }
        }
        if (Yii::$app->request->post()) {

            if(!empty($_POST['right'])){
                Rights::deleteAll(['role_id'=>$id]);
                foreach($_POST['right'] as $k => $r){
                    list($role_id,$controller_id,$action_id)= explode('_',$k);
                        $rightsmodel = new Rights();
                        $rightsmodel->role_id = $role_id;
                        $rightsmodel->controller_id = $controller_id;
                        $rightsmodel->action_id = $action_id;
                        if($rightsmodel->save()){
                           $flag = true;
                        }
                }
                if($flag){
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => 'You have updated rights for this role successfully',
                        'title' => 'Rights Updated',
                        'positonY' => 'top',
                        'positonX' => 'right'
                    ]);
                    return $this->redirect(\Yii::$app->urlManager->createUrl("setting/rights/$id"));
                }
            }

        }
        return $this->render("rights",[
            'controllers'=>$controllers,
            'roleData'=>$roleData,
            'moduleData'=>$moduleData,
            'id'=>$id,
        ]);
    }

}
