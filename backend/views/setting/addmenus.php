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

<div class="right_col" role="main" style="height: 100%;
    min-height: 928px;">
    <div class="">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add Menus</h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <?php
                $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation'=>true,
                    'options' => ['class' => 'form-horizontal form-label-left'],
                ]) ?>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Controller Name</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?= $form->field($model, 'controller_id')->widget(Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(\backend\models\Controllers::find()->all(), 'id', 'controller_name'),
                            'options' => ['placeholder' => 'Controller','onchange'=>'
                $.post( "'.Yii::$app->urlManager->createUrl('setting/set-action?id=').'"+$(this).val(), function( data ) {
                  $("#title").html( data );
                });
            '],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false);?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Action Name</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?=  $form->field($model, 'action_id')
                            ->dropDownList(
                                [],
                                ['id'=>'title']
                            )->label(false);
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Icon</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?= $form->field($model, 'icon')->label(false) ?>
                    </div>
                </div>

                <?= \yii\bootstrap\Html::submitButton('Submit', ['class'=> 'btn btn-success pull-right']) ;?>

                <?php ActiveForm::end() ?>

            </div>
        </div>
    </div>
</div>
