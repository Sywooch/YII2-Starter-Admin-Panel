<?php


/* @var $this yii\web\View */

$this->title = 'Select Payment Card';
$this->params['breadcrumbs'][] = $this->title;
//echo "<pre>".print_r($model,true)."</pre>";

?>

    <div class="container">

        <div class="row">
            <ul class="nav nav-pills nav-justified">
                <li class="active text-center"><a
                        href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('elite/search-hotel') ?>"> <i
                            class="fa fa-edit"></i> Reservation</a></li>
                <li class="text-center" style="background-color: #eee;"><a
                        href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('requests') ?>"><i
                            class="fa fa-file-text"></i> My Requests</a></li>
            </ul>
        </div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                <?php if(isset($model['HotelPaymentResponse']['size']) && !empty($model['HotelPaymentResponse']['size'])) { ?>
                <?php if(($model['HotelPaymentResponse']['size'] == 1)) {
                        $cards[0] =  $model['HotelPaymentResponse']['PaymentType'];
                    }else{
                        $cards =  $model['HotelPaymentResponse']['PaymentType'];
                    } ?>
                    <?php foreach($cards as $card):?>
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <p class="lead"><strong><?php echo $card['name']?></strong></p>
                                <div class="col-xs-9">
                                    <p class="lead"><strong><?php echo $card['code']?></strong></p>
                                </div>
                                <div class="col-xs">
                                    <span class="lead" style="text-align: right;">
                                        <?php $form = \yii\bootstrap\ActiveForm::begin(['method'=>'post']); ?>
                                                <input type="hidden" name="card_data" value='<?php echo $card['code'];?>'>
                                            <button type="submit" class="btn btn-lg btn-custom">Choose</button>
                                        <?php \yii\bootstrap\ActiveForm::end(); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
                <?php } ?>
            </div>
        </div>

    </div>
<?php

$this->registerJs(
    "
    $('.grid').masonry({
        // options
        itemSelector: '.grid-item',
        columnWidth: 200,
        gutter: 10

    });
    var elem = document.querySelector('.grid');

    var msnry = new Masonry(elem, {
        // options
        itemSelector: '.grid-item',
        columnWidth: 200
    });

    // element argument can be a selector string
    //   for an individual element
    var msnry = new Masonry('.grid', {
        // options
    });
"
);
?>