<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 2/7/16
 * Time: 5:28 PM
 */

use yii\bootstrap\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\date\DatePicker;
use kartik\select2\Select2;
?>
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <!-- start: list-group navi for large devices -->
            <div class="panel panel-default">
                <div class="panel-heading">Manage <strong>Settings</strong></div>
                <div class="list-group">
                    <a class="list-group-item" href="<?= Yii::$app->urlManager->createAbsoluteUrl('setting/index') ?>"><i class="fa fa-users"></i><span> System User </span></a>
                    <a class="list-group-item active" href="<?= Yii::$app->urlManager->createAbsoluteUrl('setting/roles') ?>"><i class="fa fa-user"></i><span> Roles </span></a>
                    <a class="list-group-item" href="javascript:void(0);"><i class="fa fa-user"></i><span> Rights </span></a>
                </div>
            </div>
            <!-- end: list-group navi for large devices -->
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Roles</strong> List <!-- check if flash message exists -->
                </div>
                <div class="panel-body">
                    <div class="help-block">
                        Here you can manage Roles which is listed on Application.
                    </div>
                    <div class="table-responsive">
                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'enableAjaxValidation' => true,
                            'enableClientValidation'=>true,
                        ]) ?>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Role Name</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <?= $form->field($model, 'role_name')->label(false) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <?= $form->field($model, 'status')->widget(SwitchInput::classname(), ['pluginOptions' => [
                                    'size' => 'small',
                                    'onColor' => 'success',
                                    'offColor' => 'danger',
                                    'onText' => '<i class="glyphicon glyphicon-ok"></i>',
                                    'offText' => '<i class="glyphicon glyphicon-remove"></i>',
                                ]])->label(false);?>
                            </div>
                        </div>

                        <?= \yii\bootstrap\Html::submitButton('Submit', ['class'=> 'btn btn-primary pull-right']) ;?>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>