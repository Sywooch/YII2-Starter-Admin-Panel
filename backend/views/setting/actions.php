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
                    <h2>Actions</h2>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <div class="pull-left">
                        <a class="btn btn-app" href="<?= \yii\helpers\Url::toRoute('setting/add-action') ?>">
                            <i class="fa fa-ellipsis-v"></i> Add Action
                        </a>
                    </div>
                    <div class="pull-right">
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
                                'attribute' => 'controller_name',
                                'format'=>'raw',
                                'value' => function ($model) {
                                    return $model->controller->controller_name;
                                },
                            ],
                            [
                                'attribute' => 'action_name',
                                'format'=>'raw',
                                'value' => function ($model) {
                                    return $model->slug;
                                },
                            ],
                            [
                                'attribute' => 'slug',
                                'format'=>'raw',
                                'value' => function ($model) {
                                    return $model->slug;
                                },
                            ],


                            //['class' => 'yii\grid\ActionColumn'],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete} ',
                                'buttons' => [

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
                                        return \yii\helpers\Url::to(['setting/edit-action/'.$key]);
                                    }
                                    if($action === 'delete'){
                                        return \yii\helpers\Url::to(['setting/delete-action/'.$key]);
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