<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

use yii\helpers\Url;

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
<body  class="error404  header_style_2 wpb-js-composer js-comp-ver-4.11.2 vc_responsive">
<?php $this->beginBody() ?>

<div id="wrapper">
    <div class="content_wrapper">
        <div class="page_404">
            <div class="bottom">
                <div class="container">
                    <h1>404</h1>
                </div>
                <div class="bottom_wr">
                    <div class="container">
                        <div class="media">
                            <div class="media-body media-middle">
                                <h3>The page you are looking for does not exist.</h3>
                            </div>
                            <div class="media-right media-middle">
                                <a href="<?php echo Yii::$app->homeUrl;?>" class="button icon_right theme_style_3 bordered">
                                    homepage								<i class="fa fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--.content_wrapper-->
    </div> <!--#wrapper-->


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
