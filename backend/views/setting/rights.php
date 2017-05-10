<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 10/5/17
 * Time: 11:30 PM
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <!-- start: list-group navi for large devices -->
                <div class="panel panel-default">
                    <div class="panel-heading">Manage <strong>Settings</strong></div>
                    <div class="list-group">
                        <a class="list-group-item" href="<?= Yii::$app->urlManager->createAbsoluteUrl('setting/index') ?>"><i class="fa fa-users"></i><span> System User </span></a>
                        <a class="list-group-item" href="<?= Yii::$app->urlManager->createAbsoluteUrl('setting/roles') ?>"><i class="fa fa-user"></i><span> Roles </span></a>
                        <a class="list-group-item active" href="<?= Yii::$app->urlManager->createAbsoluteUrl('setting/rights') ?>"><i class="fa fa-user"></i><span> Rights </span></a>
                    </div>
                </div>
                <!-- end: list-group navi for large devices -->
            </div>
            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Rights</strong> Add <!-- check if flash message exists -->
                    </div>
                    <div class="panel-body">
                        <div class="help-block">
                            Here you can manage User which is listed on Application.
                        </div>
                        <div class="table-responsive">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= \kartik\select2\Select2::widget ([
                                        'name'    => 'roles',
                                        'value'   => isset($id)?$id:"",
                                        'data'    => \yii\helpers\ArrayHelper::map (\common\models\Role::find ()->all(), 'id', 'role_name'),
                                        'options' => ['placeholder' => 'Select  Roles', 'id' => 'roles'],
                                    ]); ?>
                                </div>
                            </div>
                            <div class="col-md-8 text-right">
                                <div class="form-group">
                                    <?= \backend\widgets\SyncWidget::widget() ?>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <?php $form = \yii\widgets\ActiveForm::begin([
                                        'id' => 'rights-form',
                                        'enableAjaxValidation' => true,
                                        'enableClientValidation'=>true,
                                    ])?>

                                    <table class="table table-responsive">
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
                                                                    <div class="col-md-3" style="padding: 5px;">
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
                                    <?= \yii\bootstrap\Html::submitButton('Submit', ['class'=> 'btn btn-primary pull-right']) ;?>
                                    <?php \yii\widgets\ActiveForm::end() ?>
                                </div>
                            </div>
                        </div>
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