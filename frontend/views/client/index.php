<?php


/* @var $this yii\web\View */
$this->title = 'Choose Services';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-12">
        <a href="<?= yii\helpers\Url::to(['reservations/index'])?>">
        <div class="col-md-6">
            <div class="card card-inverse card-primary text-xs-center">
                <div class="card-block" >
                    <blockquote class="card-blockquote" style="background-color:#000000">
                        <img src="<?=Yii::$app->homeUrl;?>img/normal.png" class="img-responsive">
                    </blockquote>
                </div>
            </div>
        </div>
        </a>
        <a href="<?= yii\helpers\Url::to(['elite/search-hotel'])?>" data-method="post">
            <div class="col-md-6">
                <div class="card card-inverse card-success text-xs-center">
                    <div class="card-block">
                        <blockquote class="card-blockquote" style="background-color: #aaaaaa;">
                            <img src="<?=Yii::$app->homeUrl;?>img/elite.png" class="img-responsive">
                        </blockquote>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>