<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 20/5/16
 * Time: 5:29 PM
 */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="login-container">
    <?php $this->beginBody() ?>

    <!-- start: show content (and check, if exists a sublayout -->

    <div class="container" style="text-align: center;">

        <h1 id="app-title" class="animated fadeIn"><?= APP_NAME; ?></h1>
        <br>

        <?= $content; ?>

        <br>
    </div>

    <!-- end: show content -->
    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage() ?>