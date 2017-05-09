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
<div id="wrapper">
    <div id="login" class=" form">
        <section class="login_content">
            <?php $form = ActiveForm::begin(
                [
                    'enableAjaxValidation' => true,
                    'enableClientValidation'=>true
                ]
            ); ?>
            <!--<form>-->
            <h1><?=APP_NAME;?></h1>
            <div>
                <?= $form->field($model, 'email')->textInput(['maxlength' => true,'placeholder'=>"Your email here"]); ?>
            </div>

            <div>
                <?= Html::submitButton("Send me my Password", ['class' => 'btn btn-default submit']) ?>
                    <!--<a class="reset_pass" href="#">Lost your password?</a>-->
                <?php echo \yii\helpers\Html::a(
                    'Back to Login?',
                    \yii\helpers\Url::to(['site/login'])
                );?>
            </div>
            <div class="clearfix"></div>
            <div class="separator">
                <div class="clearfix"></div>
                <br />
                <div>
                    <h1><i class="fa fa-futbol-o" style="font-size: 26px;"></i> <?= COMPANY_NAME;?></h1>

                    <p>©<?=date("Y")?> All Rights Reserved. <?= COMPANY_NAME;?></p>
                </div>
            </div>
            <!--</form>-->
            <?php ActiveForm::end(); ?>
        </section>
    </div>
</div>
<style>
    /*.img-circle {
        width: 120px;
        height: 120px;
        border: 2px solid #51D2B7;}*/
</style>



