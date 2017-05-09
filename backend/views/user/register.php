<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 24/5/16
 * Time: 7:19 PM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?php echo \Yii::$app->view->renderFile('@backend/views/layouts/message_panel.php');?>
<div class="panel panel-default animated bounceIn" id="login-form"
     style="max-width: 300px; margin: 0 auto 20px; text-align: left;">

    <div class="panel-heading"><strong>Sign </strong> up</div>

    <div class="panel-body">


        <p>Don't have an account? Join the network by entering your e-mail address.</p>
        <?php $form = ActiveForm::begin(); ?>
        <!--<form>-->
        <h1><?= APP_NAME; ?></h1>

        <div>
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true, 'placeholder' => 'First Name'])->label(false); ?>
        </div>
        <div>
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true, 'placeholder' => 'Last Name'])->label(false); ?>
        </div>
        <div>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email'])->label(false); ?>
        </div>
        <div>
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder' => 'Password'])->label(false); ?>
        </div>
        <div>
            <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true, 'placeholder' => 'Confirm Password'])->label(false); ?>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <?= Html::submitButton("Register", ['class' => 'btn btn-large btn-primary btn-block']); ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>