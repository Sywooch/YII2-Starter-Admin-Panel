<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 10/5/17
 * Time: 11:30 PM
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$user = \common\models\User::findUserByID($model->user_id);
?>
<div class="container profile-layout-container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-profile">
                <div class="panel-profile-header">
                    <div class="image-upload-container" style="width: 100%; height: 100%; overflow:hidden;">
                        <!-- profile image output-->
                        <img class="img-profile-header-background" id="user-banner-image"
                             src="<?= Yii::$app->homeUrl?>/default_image/default_banner.png" width="100%" style="width: 100%; max-height: 192px;">
                        <!-- check if the current user is the profile owner and can change the images -->
                        <div class="image-upload-loader" id="banner-image-upload-loader" style="padding: 50px 350px;">
                            <div class="progress image-upload-progess-bar" id="banner-image-upload-bar">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="00"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                </div>
                            </div>
                        </div>
                        <!-- show user name and title -->
                        <div class="img-profile-data">
                            <h1><?= $user->profile->first_name . " " . $user->profile->last_name; ?></h1>

                            <h2><?= $user->role->role_name;?></h2>
                        </div>
                    </div>
                    <div class="image-upload-container profile-user-photo-container"
                         style="width: 140px; height: 140px;">
                        <a href="javascript:void(0);">
                            <img class="img-rounded profile-user-photo" id="user-profile-image"
                                 src="<?=DEFAULT_IMAGE;?>"
                                 data-src="holder.js/140x140" alt="140x140" style="width: 140px; height: 140px;">
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="panel-profile-controls">
                        <!-- start: User statistics -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="controls controls-header pull-right">
                                    <a class="btn btn-primary edit-account" href="javascript:void(0);"><i class="fa fa-edit"></i> Edit account</a>
                                    <a class="btn btn-warning" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('setting/index')?>"><i class="fa fa-hand-o-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 layout-nav-container">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Profile</strong> menu</div>
                <div class="list-group">
                    <a class="list-group-item" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('setting/view-user/'.$user->id)?>"><span>About</span></a>
                    <a class="list-group-item active" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('setting/change-user-password/'.$user->id)?>"><span>Change Password</span></a>
                </div>
            </div>
        </div>

        <div class="col-md-10 layout-content-container">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Change User </strong> Password</div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="profile-category-1">
                            <?php $form = ActiveForm::begin([
                                //'id'=>"change-password",
                                'enableAjaxValidation' => true,
                                'enableClientValidation'=>true]); ?>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?= Html::submitButton("Change Password", ['class' => 'btn btn-primary pull-right submit']); ?>
                                    </div>
                                </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>