<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 2/7/16
 * Time: 5:18 PM
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
                    <h2>Menus</h2>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <div class="pull-left">
                        <a class="btn btn-app" href="<?= \yii\helpers\Url::toRoute('setting/add-menu') ?>">
                            <i class="fa fa-bars"></i> Add Menu
                        </a>
                    </div>
                    <div class="pull-right">
                        <?php if(DEVLOPMENT_ENV): ?>
                        <a class="btn btn-app" href="<?= \yii\helpers\Url::toRoute('setting/actions') ?>">
                            <i class="fa fa-ellipsis-h"></i> Actions
                        </a>
                        <a class="btn btn-app" href="<?= \yii\helpers\Url::toRoute('setting/controllers') ?>">
                            <i class="fa fa-ellipsis-v"></i> Controllers
                        </a>
                        <?php endif;?>
                        <a class="btn btn-app" href="<?= \yii\helpers\Url::toRoute('setting/index') ?>">
                            <i class="fa fa-hand-o-left"></i> Back to Settings
                        </a>
                    </div>
                    <?php Pjax::begin(['id' => 'menus']) ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'summary' => "",
                        'showOnEmpty'=>true,
                        //'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'controller_id',
                                'format'=>'raw',
                                'value' => function ($model) {
                                    //return ($model->status)?"<span class='label label-success pull-right'>Active</span>":"InActive";
                                    return $model->controller->slug;
                                },
                            ],
                            [
                                'attribute' => 'action_id',
                                'format'=>'raw',
                                'value' => function ($model) {
                                    //return ($model->status)?"<span class='label label-success pull-right'>Active</span>":"InActive";
                                    return $model->action->action_name." (".$model->action->slug.")";
                                },
                            ],
                            [
                                'attribute' => 'icon',
                                'format'=>'raw',
                                'value' => function ($model) {
                                    //return ($model->status)?"<span class='label label-success pull-right'>Active</span>":"InActive";
                                    return  $model->icon." ".Html::tag('span', "", ['class' => [$model->icon,"fa-2x"]]);
                                },
                            ],
                            [
                                'attribute' => 'order',
                                'format'=>'raw',
                                'value' => function ($model) {
                                    //return ($model->status)?"<span class='label label-success pull-right'>Active</span>":"InActive";
                                   return $model->order;
                                },
                            ],


                            //['class' => 'yii\grid\ActionColumn'],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete} {up} {down}  ',
                                'buttons' => [
                                    'up' => function ($url, $model) {
                                        if($model->order != 1){
                                            return Html::a('<span class="glyphicon glyphicon-chevron-up" style="color:green"></span>', $url,
                                                [
                                                    'class'          => 'ajaxorderup',
                                                    'title' => Yii::t('app', 'Up'),
                                                    'status-url'     => $url,
                                                    'pjax-container' => 'menus',
                                                    'data-pjax'=>'w0',
                                                ]);
                                        }

                                    },
                                    'down' => function ($url, $model) {
                                        $max =  \backend\models\Menus::find()->select('MAX(`order`)')->scalar();
                                        if($model->order != $max ){
                                            return Html::a('<span class="glyphicon glyphicon-chevron-down" style="color:#b30000"></span>', $url,
                                                [
                                                    'class'          => 'ajaxorderdown',
                                                    'title' => Yii::t('app', 'Down'),
                                                    'status-url'     => $url,
                                                    'pjax-container' => 'menus',
                                                    'data-pjax'=>'w0',
                                                ]);

                                        }
                                    },
                                    'delete'=>function ($url,$model){
                                        return Html::a('<span class="glyphicon glyphicon-remove" style="color:#b30000"></span>', $url,
                                            [
                                                'class'          => 'ajaxdelete',
                                                'title' => Yii::t('app', 'Status'),
                                                'status-url'     => $url,
                                                'pjax-container' => 'menus',
                                                'data-pjax'=>'w0',
                                            ]);
                                    }

                                ],
                                'urlCreator' => function ($action, $model, $key, $index) {

                                    if ($action === 'update') {
                                        return \yii\helpers\Url::to(['setting/edit-menu/'.$key]);
                                    }
                                    if($action === 'up'){
                                        return \yii\helpers\Url::to(['setting/up/'.$key]);
                                    }
                                    if($action === 'down'){
                                        return \yii\helpers\Url::to(['setting/down/'.$key]);
                                    }
                                    if($action === 'delete'){
                                        return \yii\helpers\Url::to(['setting/delete-menu/'.$key]);
                                    }
                                }
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end() ?>

                </div>
            </div>
        </div>
    </div>
<?php
$this->registerJs("
$(document).on('ready pjax:success', function () {
    $('.ajaxorderup').on('click', function (e) {
        e.preventDefault();
        var Url     = $(this).attr('status-url');
        var pjaxContainer = $(this).attr('pjax-container');
            $.ajax({
                    url:   Url,
                    type:  'post',
                    error: function (xhr, status, error) {
                        alert('There was an error with your request.'
                            + xhr.responseText);
                        }
                }).done(function (data) {
                  $.pjax.reload({container: '#' + $.trim(pjaxContainer)});
                });

    });
});
$(document).on('ready pjax:success', function () {
    $('.ajaxorderdown').on('click', function (e) {
        e.preventDefault();
        var Url     = $(this).attr('status-url');
        var pjaxContainer = $(this).attr('pjax-container');
            $.ajax({
                    url:   Url,
                    type:  'post',
                    error: function (xhr, status, error) {
                        alert('There was an error with your request.'
                            + xhr.responseText);
                        }
                }).done(function (data) {
                  $.pjax.reload({container: '#' + $.trim(pjaxContainer)});
                });

    });
});
$(document).on('ready pjax:success', function () {
    $('.ajaxdelete').on('click', function (e) {
        e.preventDefault();
        var Url     = $(this).attr('status-url');
        var pjaxContainer = $(this).attr('pjax-container');
        if (!confirm('Do you want to delete?')){
            return false;
        }
            $.ajax({
                    url:   Url,
                    type:  'post',
                    error: function (xhr, status, error) {
                        alert('There was an error with your request.'
                            + xhr.responseText);
                        }
                }).done(function (data) {
                  $.pjax.reload({container: '#' + $.trim(pjaxContainer)});
                });

    });
});
");
?>