<?php


/* @var $this yii\web\View */

$this->title = 'List Hotels';
$this->params['breadcrumbs'][] = $this->title;
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
            <div id="products" class="row list-group grid">
<?php if (isset($model['HotelListResponse']['HotelList'])) { ?>
    <?php if (isset($model['HotelListResponse']['HotelList']['size']) && ($model['HotelListResponse']['HotelList']['size'] > 1) ) { ?>
        <?php if (isset($model['HotelListResponse']['HotelList']['HotelSummary'])) { ?>
            <?php $hotels = $model['HotelListResponse']['HotelList']['HotelSummary']; ?>
            <?php //Yii::$app->session->set('hotel_result',$hotels);?>
            <?php //echo "<pre>".print_r(isset($hotels[0])?$hotels[0]:'no',true).'</pre>';?>

            <?php foreach ($hotels as $hotel):?>
                <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['elite/hotel-details','id'=>$hotel['hotelId']])?>">

                <div class="col-md-6 grid-item">

                    <div class="thumbnail">
                        <div class="caption">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="<?php echo isset($hotel['thumbNailUrl'])?\frontend\models\Url::MEDIA_URL.$hotel['thumbNailUrl']:'no'?>" class="img-responsive img-rounded"
                                         alt="Cinque Terre">
                                </div>
                                <div class="col-md-8">
                                    <ul class="list-unstyled">
                                        <li><p class="h4"><?php echo $hotel['name']?></p></li>
                                        <li><p><?php echo isset($hotel['address1'])?$hotel['address1']:'N/A';?>, <?php echo isset($hotel['city'])?$hotel['city']:'N/A'?>, <?php echo isset($hotel['stateProvinceCode'])?$hotel['stateProvinceCode']:'N/A'?></p></li>
                                        <li>
                                            <span style="font-weight: bold;color: #000;">Min Rate: <?php echo isset($hotel['lowRate'])?'$ '.$hotel['lowRate']:'N/A';?></span>

                                            <span class="stars"><?php echo isset($hotel['hotelRating'])?$hotel['hotelRating']:0?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                </a>
            <?php endforeach;?>
        <?php } ?>
    <?php }else{ ?>

    <?php } ?>
<?php } ?>

            </div>

        </div>

    </div>

</div>
<?php $this->registerJs("
$.fn.stars = function() {
    return $(this).each(function() {
        // Get the value
        var val = parseFloat($(this).html());
        // Make sure that the value is in 0 - 5 range, multiply to get width
        var size = Math.max(0, (Math.min(5, val))) * 16;
        // Create stars holder
        var _span = $('<span />').width(size);
        // Replace the numerical value with stars
        $(this).html(_span);
    });
}
$(function() {
    $('span.stars').stars();
    setTimeout(function(){
        $('.grid').masonry({
             itemSelector: '.grid-item', // use a separate class for itemSelector, other than .col-
             percentPosition: true,
        });
    }, 3000);
});


");
?>