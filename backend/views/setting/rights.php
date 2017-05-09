<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 17/6/16
 * Time: 1:04 AM
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

?>
<?php echo \Yii::$app->view->renderFile('@backend/views/layouts/message_panel.php');?>
<div class="right_col" role="main" style="height: 100%;
    min-height: 928px;">
    <div class="">
        <div class="x_panel">
            <div class="x_title">
                <h2>Roles & Rights</h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-8">
                        <?= \backend\widgets\SyncWidget::widget() ?>
                    </div>
                    <div class="col-md-4 text-right">
                        <a class="btn btn-app" href="<?= \yii\helpers\Url::toRoute ('setting/index') ?>">
                            <i class="fa fa-hand-o-left"></i> Back to Settings
                        </a>
                    </div>
                </div>
                <div class="pull-left">
                    <div class="form-group" style="width: 230px !important;">
                        <?=
                        \kartik\select2\Select2::widget ([
                            'name'    => 'roles',
                            'value'   => isset($id)?$id:"",
                            'data'    => \yii\helpers\ArrayHelper::map (\common\models\Role::find ()->all(), 'id', 'role_name'),
                            'options' => ['placeholder' => 'Select  Roles', 'id' => 'roles'],
                        ]);
                        ?>

                    </div>
                </div>
                <div>
                    <?php $form = \yii\widgets\ActiveForm::begin([
                        'id' => 'rights-form',
                        'enableAjaxValidation' => true,
                        'enableClientValidation'=>true,
                    ])?>

                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th style="font-size: large;font-weight: bold">Give All Permission</th>
                            <th class="text-center"><input type="checkbox" id="whole_select"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <?php foreach($controllers  as $controller):?>
                                    <table style="border: none;" class="table table-responsive">
                                        <thead>
                                        <tr>
                                            <th><span style="font-size: large; font-weight: bold"><?=$controller->slug?></span></th>
                                            <th class="text-right" style="width: 50%"><input type="checkbox" id="<?=$controller->controller_name?>" class="flat"> ( Select All )</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <?php foreach($controller->actions  as $action):?>
                                                        <div class="col-md-3">
                                                            <div class="col-md-10 text-left"><?=$action->slug?></div>
                                                            <div class="col-md-2 text-right"><input type="checkbox" <?= (Yii::$app->commonfunction->ifChecked($roleData,['role'=> isset($id)?$id:"",'controller'=>$controller->id,'action'=>$action->id]))?"checked":""; ?> name="right[<?=isset($id)?$id."_".$controller->id."_".$action->id:""."_".$controller->id."_".$action->id?>]" class="flat <?=$controller->controller_name?>"></div>
                                                        </div>
                                                    <?php endforeach;?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php $this->registerJs ("
                                        $(document).ready(function(){    
                                         $(function () {
                                            var checkAll = $('input#".$controller->controller_name."');
                                            var checkboxes = $('input.".$controller->controller_name."');
                                            var whole = $('#whole_select');
                                                whole.on('ifChecked ifUnchecked', function(event) {
                                                    if (event.type == 'ifChecked') {
                                                        checkboxes.iCheck('check');
                                                        checkAll.iCheck('check');
                                                    } else {
                                                        checkboxes.iCheck('uncheck');
                                                        checkAll.iCheck('uncheck');
                                                    }
                                                });
                                                checkAll.on('ifChecked ifUnchecked', function(event) {
                                                    if (event.type == 'ifChecked') {
                                                        checkboxes.iCheck('check');
                                                    } else {
                                                        checkboxes.iCheck('uncheck');
                                                    }
                                                });
                                                checkboxes.on('ifChanged', function(event){
                                                    if(checkboxes.filter(':checked').length == checkboxes.length) {
                                                        checkAll.prop('checked', 'checked');
                                                    } else {
                                                        checkAll.prop('checked',false);
                                                        whole.prop('checked',false);
                                                    }
                                                    checkAll.iCheck('update');
                                                    whole.iCheck('update');
                                                });
                                            });            
                                        });
                                    "); ?>
                                <?php endforeach;?>
                            </td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <?= \yii\bootstrap\Html::submitButton('Submit', ['class'=> 'btn btn-success pull-right']) ;?>
                    <?php \yii\widgets\ActiveForm::end() ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs ("
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
  });
});

$('#roles').change(function(){
    if(this.value != undefined && this.value !=''){
    var v = this.value;
       window.location.href='". \Yii::$app->urlManager->createUrl('setting/rights')."/'+v;
    }
});

");
?>