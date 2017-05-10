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
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <!-- start: list-group navi for large devices -->
                <div class="panel panel-default">
                    <div class="panel-heading">Manage <strong>Settings</strong></div>
                    <div class="list-group">
                        <a class="list-group-item" href="<?= Yii::$app->urlManager->createAbsoluteUrl('setting/index') ?>"><i class="fa fa-users"></i><span> System User </span></a>
                        <a class="list-group-item active" href="<?= Yii::$app->urlManager->createAbsoluteUrl('setting/roles') ?>"><i class="fa fa-user"></i><span> Roles </span></a>
                        <a class="list-group-item" href="<?= Yii::$app->urlManager->createAbsoluteUrl('setting/rights') ?>"><i class="fa fa-user"></i><span> Rights </span></a>
                    </div>
                </div>
                <!-- end: list-group navi for large devices -->
            </div>
            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Roles</strong> List <!-- check if flash message exists -->
                    </div>
                    <div class="panel-body">
                        <div class="help-block">
                            Here you can manage Roles which is listed on Application.
                        </div>
                        <div class="table-responsive">
                            <div class="pull-right">
                                <a class="btn btn-primary" href="<?= Yii::$app->urlManager->createAbsoluteUrl('setting/add-role') ?>"><i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;&nbsp;Add Role</a>
                            </div>
                            <?php Pjax::begin(['id' => 'roles']) ?>
                            <?= \yii\grid\GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'layout' => "{pager}\n{summary}\n{items}",
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'role_name',
                                    'created_at:datetime',
                                    [
                                        'attribute' => 'status',
                                        'format'=>'raw',
                                        'value' => function ($model) {
                                            //return ($model->status)?"<span class='label label-success pull-right'>Active</span>":"InActive";
                                            return ($model->status)? Html::tag('span', 'Active', ['class' => ['label','label-success']]):Html::tag('span', 'InActive', ['class' => ['label','label-danger']]);
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{update} {status} ',
                                        'buttons' => [
                                            'status' => function ($url, $model) {
                                                if($model->status == ACTIVE){
                                                    return Html::a('<i class="fa fa-times"></i>', $url,
                                                        [
                                                            'class'          => 'ajaxStatus btn btn-danger btn-xs tt',
                                                            'title' => Yii::t('app', 'Status'),
                                                            'status-url'     => $url,
                                                            'pjax-container' => 'roles',
                                                            'data-pjax'=>'w0',
                                                        ]);
                                                }else{
                                                    return Html::a('<i class="fa fa-check"></i>', $url,
                                                        [
                                                            'class'          => 'ajaxStatus btn btn-success btn-xs tt',
                                                            'title' => Yii::t('app', 'Status'),
                                                            'data-pjax'=>'w0',
                                                            'status-url'     => $url,
                                                            'pjax-container' => 'roles',
                                                        ]);
                                                }

                                            },
                                            'update' => function ($url, $model) {

                                                return Html::a('<i class="fa fa-pencil"></i>', $url,
                                                    [
                                                        'class'          => 'btn btn-primary btn-xs tt',
                                                        'title' => Yii::t('app', 'Update'),
                                                        'data-pjax'=>'w0',
                                                        'status-url'     => $url,
                                                        'pjax-container' => 'roles',
                                                    ]);

                                            },
                                        ],
                                        'urlCreator' => function ($action, $model, $key, $index) {

                                            if ($action === 'update') {
                                                return \yii\helpers\Url::to(['setting/edit-role/'.$key]);
                                            }
                                            if ($action === 'status') {
                                                return \yii\helpers\Url::to(['setting/status-role/'.$key]);
                                            }
                                        }
                                    ],
                                ],
                                'tableOptions' => ['class' => 'table table-hover'],
                            ]); ?>
                            <?php \yii\widgets\Pjax::end() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$this->registerJs("
$(document).on('ready pjax:success', function () {
    $('.ajaxStatus').on('click', function (e) {
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
"); ?>