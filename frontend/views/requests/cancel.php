<?php


/* @var $this yii\web\View */

$this->title = 'Room Details';
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
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                <?php if (isset($model)) { ?>
                    <div id="products" class="row list-group grid">
                        <?php $form = \yii\bootstrap\ActiveForm::begin([
                            'method'=>'post',
                            'id'=>'cancel-form'
                        ]); ?>
                        <div class="item col-md-8 col-md-offset-2 grid-item">
                            <div class="thumbnail">

                                <div class="caption">
                                    <p class="lead"><strong>Itinerary Details </strong></p>

                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Itinerary #</strong> <?= $model['iternary_id']?></li> |
                                        <li><strong>Created Email</strong> <?= $model['email']?></li>
                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><textarea class="form-control reason" style="width: 700px;" placeholder="i.e. Reason for cancellation" name="canceled[reason]" required ></textarea></li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                        <div class="item col-md-8 col-md-offset-2">

                            <span class="lead">
                                <button type="submit" class="btn btn-lg btn-block btn-custom" data-method="post">Cancel Reservation</button>
                            </span>

                        </div>
                        <?php \yii\bootstrap\ActiveForm::end(); ?>

                    </div>
                <?php } ?>
            </div>
        </div>

    </div>
<?php $this->registerJs("
$( '#cancel-form' ).submit(function( event ) {

    if($('.reason').val() == ''){
        alert('Reason can not be blank')
        event.preventDefault();
    }
});
")?>