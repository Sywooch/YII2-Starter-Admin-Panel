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
use common\models\Role;
use common\models\Media;
?>

<?php $form = ActiveForm::begin([
    'id' => 'client-form',
    'enableAjaxValidation' => true,
    'enableClientValidation'=>true,
]) ?>
<div class="row">
    <div class="col-md-6">
        <div class="form-group field-salonform-name required">
            <label class="control-label" for="salonform-name">First Name</label>
            <?= $form->field($model, 'first_name')->textInput(['placeholder'=>"i.e, John",'class'=>'form-control  required'])->label(false); ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group field-salonform-name required">
            <label class="control-label" for="salonform-name">Last Name</label>
            <?= $form->field($model, 'last_name')->textInput(['placeholder'=>"i.e, Doe",'class'=>'form-control  required'])->label(false); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group field-salonform-area required">
            <label class="control-label" for="salonform-area">Email</label>
            <?= $form->field($model, 'email')->textInput(['id'=>'user_email','placeholder'=>"i.e, john@comapany.com",'class'=>'form-control  required'])->label(false); ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group field-salonform-address required">
            <label class="control-label" for="salonform-address">Phone No</label>
            <?= $form->field($model, 'phone_no')->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '(999) 999-9999',
            ])->label(false); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group field-salonform-address required">
            <label class="control-label" for="salonform-address">Gender</label>
            <?= $form->field($model, 'gender')->widget(Select2::classname(), [
                'data' =>[0=>'Not Specified',1=>'Male',2=>'Female'],
                'options' => ['placeholder' => 'Select Gender'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false);?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group field-salonform-area required">
            <label class="control-label" for="salonform-area">Date Of Birth</label>
            <?= $form->field($model, 'date_of_birth')->widget(
                DatePicker::className(), [
                'options' => ['placeholder' => 'Select Birth date'],
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                ]
            ])->label(false);?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group field-salonform-address required">
            <label class="control-label" for="salonform-address">Roles</label>
            <?php echo $form->field($model, 'role')->widget(
                \kartik\select2\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(Role::find()->where(['not in','id',[SUPER_ADMIN,CLIENT,CLIENT_APP_USER]])->asArray()->all(), 'id', 'role_name'),
                'options' => ['placeholder' => 'Select Role ...'],
            ])->label(false); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary pull-right">Save</button>
</div>
<?php ActiveForm::end() ?>
