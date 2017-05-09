<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 24/5/16
 * Time: 9:06 PM
 */
?>
<div class="top_nav">

    <div class="nav_menu">
        <nav class="" role="navigation">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?=DEFAULT_IMAGE;?>" alt=""><?= \Yii::$app->user->identity->first_name." ".\Yii::$app->user->identity->last_name;?>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="<?=\yii\helpers\Url::toRoute("user/profile")?>">  Profile</a>
                        </li>
                        <li>
                            <a href="<?php echo \Yii::$app->urlManager->createUrl("setting")?>">
                                <span>Settings</span>
                            </a>
                        </li>
                        <li><?php echo \yii\helpers\Html::a(
                                'Logout',
                                \yii\helpers\Url::to(['site/logout']),
                                [
                                    'data-confirm' => "Want to logout?", // <-- confirmation works...
                                    'data-method' => 'post',
                                ]
                            );?>
                        </li>
                    </ul>
                </li>

                <li role="presentation" data-toggle="tooltip" title="Request" class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle info-number" data-toggle="tooltip" data-placement="left" title="Notification">
                        <i class="fa fa-ticket" style="font-size: 26px;"></i>
                       <?php if(!true){?> <span class="badge bg-green">6</span><?php } ?>
                    </a>
                </li>

            </ul>
        </nav>
    </div>

</div>

