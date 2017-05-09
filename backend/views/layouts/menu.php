<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 24/5/16
 * Time: 9:00 PM
 */?>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="index.html" class="site_title"><span><?=APP_NAME?></span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile">
            <div class="profile_pic">
                <img src="<?=DEFAULT_IMAGE;?>" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2><?= \Yii::$app->user->identity->first_name." ".\Yii::$app->user->identity->last_name;?></h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Menus</h3>
                <ul class="nav side-menu">
                      <?php $menus = \backend\models\Menus::find()->orderBy('order')->all( );
                      foreach($menus as $menu){
                          $url = $menu->controller->controller_name."/".$menu->action->action_name
                      ?>
                    <li>
                        <a href="<?=\yii\helpers\Url::toRoute($url)?>"><i class="fa <?=$menu->icon?>"></i> <?=$menu->controller->slug?> </a>
                    </li>
                    <?php }?>
                </ul>
            </div>

        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings" href="<?php echo \Yii::$app->urlManager->createUrl("setting")?>">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" onclick="maxWindow();" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock" href="<?php echo \yii\helpers\Url::toRoute('site/lock')?>">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <?php echo \yii\helpers\Html::a(
                '<span class="glyphicon glyphicon-off" aria-hidden="true"></span>',
                \yii\helpers\Url::to(['site/logout']),
                [
                    'data-confirm' => "Want to logout?", // <-- confirmation works...
                    'data-method' => 'post',
                    'data-toggle'=>'tooltip',
                    'data-placement'=>'top',
                    'title'=>'Logout'

                ]
            );?>
            <!--<a data-toggle="tooltip" data-placement="top" title="Logout">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>-->
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
