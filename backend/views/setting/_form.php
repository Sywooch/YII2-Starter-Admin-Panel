<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 10/5/17
 * Time: 11:30 PM
 */
use yii\bootstrap\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\date\DatePicker;
use kartik\select2\Select2;
?>
<?php
$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal form-label-left'],
]) ?>

<div class="form-group">
<label class="control-label col-md-3 col-sm-3 col-xs-12">Default Input</label>
    <div class="col-md-9 col-sm-9 col-xs-12">
<?= $form->field($model, 'username')->label(false) ?>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Default Input</label>
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

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Default Input</label>
    <div class="col-md-9 col-sm-9 col-xs-12">
        <?= $form->field($model, 'email')->passwordInput()->label(false) ?>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Default Input</label>
    <div class="checkbox">
        <label>
            <?= $form->field($model, 'status')->checkbox(["class"=>"flat"])->label(false) ?>
        </label>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Default Input</label>
    <div class="col-md-9 col-sm-9 col-xs-12">

            <?= $form->field($model, 'created_at')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Enter birth date ...'],
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                   /* 'calendarWeeks' => true,*/
                    /*'daysOfWeekDisabled' => [0, 6],*/
                    'format' => 'dd-M-yyyy',
                    'autoclose' => true,
                ]
            ])->label(false);
            ?>
        </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Default Input</label>
    <div class="col-md-9 col-sm-9 col-xs-12">

        <?= $form->field($model, 'screenlock')->widget(Select2::classname(), [
            'data' => ["0"=>"admin","1"=>"superadmin"],
            'options' => ['placeholder' => 'Select a state ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(false);
        ?>
    </div>
</div>

<?= \yii\bootstrap\Html::submitButton('Submit', ['class'=> 'btn btn-primary']) ;?>

<?php ActiveForm::end() ?>
