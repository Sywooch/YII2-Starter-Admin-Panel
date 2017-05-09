<?php

$menuArr = [
    [
        'url'=>Yii::$app->urlManager->createAbsoluteUrl('dashboard'),
        'active'=> \backend\components\helpers\Helper::isMenuActive('dashboard'),
        'icon'=>'fa fa-tachometer',
        'slug'=>'Dashboard'
    ],
];
?>

<div id="topbar-second" class="topbar">
    <div class="container">
        <?= \backend\widgets\MenuWidget::widget(['menuArr'=>$menuArr]);?>
    </div>
</div>