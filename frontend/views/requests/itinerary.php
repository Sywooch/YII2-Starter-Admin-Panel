<?php


/* @var $this yii\web\View */

$this->title = 'Reservation Requests';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">

    <div class="row">
        <ul class="nav nav-pills nav-justified">
            <li class="text-center" style="background-color: #eee;"><a
                    href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('elite/search-hotel') ?>"> <i
                        class="fa fa-edit"></i> Reservation</a></li>
            <li class="active text-center"><a
                    href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('requests') ?>"><i
                        class="fa fa-file-text"></i> My Requests</a></li>
        </ul>
    </div>
    <div class="row grid" style="margin-top: 20px;">
        <?php foreach($model as $m):?>
            <div class="col-md-4 grid-item">
                <div class="thumbnail">
                    <div class="caption">
                        <p class="lead"><strong><?php echo $m->hotel_name?></strong></p>
                        <div class="col-xs-9">
                            <ul class="list-unstyled">
                                <li>Itinerary# : <strong><?php echo $m->iternary_id ?></strong></li>
                                <li><strong><?php echo $m->check_in_date ?> to <?php echo $m->check_out_date ?></strong></li>
                                <li>Created On: <strong><?php echo date('m/d/Y h:i a',$m->created_at) ?></strong></li>
                            </ul>
                        </div>
                        <div class="col-xs">
                                    <span class="lead" style="text-align: right;">
                                        <?php $form = \yii\bootstrap\ActiveForm::begin(['method'=>'post']); ?>
                                        <input type="hidden" name="iternary_id" value='<?php echo $m->iternary_id;?>'>
                                        <input type="hidden" name="user_email" value='<?php echo $m->user_email;?>'>
                                        <input type="hidden" name="no_rooms" value='<?php echo $m->no_rooms;?>'>
                                            <button type="submit" class="btn btn-lg btn-custom">Choose</button>
                                        <?php \yii\bootstrap\ActiveForm::end(); ?>
                                    </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>

</div>
<?php $this->registerJs("

$(function() {
    setTimeout(function(){
        $('.grid').masonry({
             itemSelector: '.grid-item', // use a separate class for itemSelector, other than .col-
             percentPosition: true,
        });
    }, 1500);
});


");
?>