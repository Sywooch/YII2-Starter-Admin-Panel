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
<div class="panel panel-default animated bounceIn" id="login-form"
     style="max-width: 300px; margin: 0 auto 20px; text-align: left;">

    <div class="panel-heading"><strong>Please</strong> Unlock</div>

    <div class="panel-body">


        <p>Use your password to unlock this screen.</p>
        <?php $form = ActiveForm::begin(); ?>
        <!--<form>-->
        <div class="text-center">
            <div><img class="img-circle" src="<?= DEFAULT_IMAGE; ?>" alt=""></div>
            <h3><?= \Yii::$app->user->identity->first_name . " " . \Yii::$app->user->identity->last_name ?></h3>
        </div>
        <div>
            <?= $form->field($model, 'username')->hiddenInput(['maxlength' => true])->label(false); ?>
        </div>
        <div>
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder' => 'password'])->label(false); ?>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <?= Html::submitButton("Unlock me", ['class' => 'btn btn-large btn-primary']); ?>
            </div>
            <div class="col-md-8 text-right">
                <small>
                    Want to logout? <?php echo \yii\helpers\Html::a(
                        'Logout',
                        \yii\helpers\Url::to(['site/logout']),
                        [
                            'data-confirm' => "Want to logout?", // <-- confirmation works...
                            'data-method' => 'post',
                            'class' => 'reset_pass'
                        ]); ?>
                </small>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="separator">
            <div class="clearfix"></div>
            <br/>

            <div>
                <h1><i class="fa fa-futbol-o" style="font-size: 26px;"></i> <?= COMPANY_NAME; ?></h1>

                <p>© <?= date("Y") ?> All Rights Reserved. <?= COMPANY_NAME; ?></p>
            </div>
        </div>
        <!--</form>-->
        <?php ActiveForm::end(); ?>
    </div>

</div>