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

    <div class="panel-heading"><strong>Please</strong> sign in</div>

    <div class="panel-body">


        <p>If you're already a member, please login with your username/email and password.</p>
        <?php $form = ActiveForm::begin(); ?>
        <!--<form>-->
        <h1><?= APP_NAME; ?></h1>

        <div>
            <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'placeholder' => 'username or email'])->label(false); ?>
        </div>
        <div>
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder' => 'password'])->label(false); ?>
        </div>

        <hr>
        <div class="row">
            <div class="col-md-4">
                <?= Html::submitButton("Login Now", ['class' => 'btn btn-large btn-primary']); ?>
            </div>
            <div class="col-md-8 text-right">
                <small>
                    Forgot your password? <a
                        href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/forgot-password') ?>"><br>Create
                        a new one.</a>
                </small>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>