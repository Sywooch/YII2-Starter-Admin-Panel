<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 17/6/16
 * Time: 2:32 PM
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?php echo \Yii::$app->view->renderFile('@backend/views/layouts/message_panel.php');?>
<div class="right_col" role="main">
    <div class="">
        <div class="x_panel" style="height:768px;">
            <div class="x_title">
                <h2>Change Password</h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php $form = ActiveForm::begin([
                    //'id'=>"change-password",
                    'enableAjaxValidation' => true,
                    'enableClientValidation'=>true]); ?>
                <!--<form>-->
                <div>
                    <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>
                </div>
                <div>
                    <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true]) ?>
                </div>
                <div>
                    <?= Html::submitButton("Change Password", ['class' => 'btn btn-default submit']); ?>
                </div>
                <div class="clearfix"></div>
                <!--</form>-->
                <?php ActiveForm::end(); ?>

                <!-- end form for validations -->

            </div>
        </div>
    </div>
</div>
