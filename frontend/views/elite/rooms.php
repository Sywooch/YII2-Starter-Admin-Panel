<?php


/* @var $this yii\web\View */

$this->title = 'Available Rooms';
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
            <?php if (isset($model['HotelRoomAvailabilityResponse']['size']) && ($model['HotelRoomAvailabilityResponse']['size'] >= 1)) { ?>
                <div id="products" class="row list-group grid" style="position: relative; height: 221px;">
                    <?php if($model['HotelRoomAvailabilityResponse']['size']<= 1){
                     $rooms = [$model['HotelRoomAvailabilityResponse']['HotelRoomResponse']];
                    }else{
                        $rooms = $model['HotelRoomAvailabilityResponse']['HotelRoomResponse'];
                    }?>

                    <?php foreach ($rooms as $room): ?>
                        <div class="item  col-xs-6 col-lg-6 grid-item">
                            <div class="thumbnail">
                                <div class="caption">
                                    <div class="col-xs-8">
                                        <p class="lead"><strong><?php echo isset($room['roomTypeDescription'])?$room['roomTypeDescription']:'N/A';?></strong></p>
                                    </div>
                                    <div class="col-xs-4">
                                        <span class="lead">
                                                <?php $form = \yii\bootstrap\ActiveForm::begin(['action'=> Yii::$app->urlManager->createAbsoluteUrl(['elite/room-detail','id'=>$model["hotelId"]]),'method'=>'post']); ?>
                                                <input type="hidden" name="room_type_data" value='<?php echo \yii\helpers\Json::encode($room);?>'>
                                            <button type="submit" class="btn btn-lg btn-custom">Detail Charges</button>
                                            <?php \yii\bootstrap\ActiveForm::end(); ?>
                                        </span>
                                    </div>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Note: </strong> <?php echo $room['RateInfos']['RateInfo']['cancellationPolicy']?></li>
                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Request Rooms</strong> : <?php echo $model['HotelRoomAvailabilityResponse']['numberOfRoomsRequested'];?></li>
                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Check In Date</strong> : <?php echo $model['HotelRoomAvailabilityResponse']['arrivalDate'];?></li>

                                        <li style="list-style: none">|</li>

                                        <li><strong>Check Out Date</strong> : <?php echo $model['HotelRoomAvailabilityResponse']['departureDate'];?></li>
                                    </ul>
                                    <?php
                                    // print_r($model['RateInfos']['RateInfo']['ChargeableRateInfo']['Surcharges']['size']);die;
                                    if($room['RateInfos']['RateInfo']['ChargeableRateInfo']['Surcharges']['size'] <= 1 ){
                                        $tax_service = $room['RateInfos']['RateInfo']['ChargeableRateInfo']['Surcharges']['Surcharge']['amount'];
                                    }else{
                                        $tax_service = $room['RateInfos']['RateInfo']['ChargeableRateInfo']['Surcharges']['Surcharge'][0]['amount'];
                                        $sales_tax = $room['RateInfos']['RateInfo']['ChargeableRateInfo']['Surcharges']['Surcharge'][1]['amount'];
                                    }
                                    ?>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong> Service Charges </strong> : $<?php echo $tax_service?></li>
                                        <li><strong> Sales Tax </strong> : <?php echo isset($sales_tax)?'$'.$sales_tax:' N/A'?></li>
                                        <li><strong>Total Rate (Tax & Charges including)</strong> : $<?php echo $room['RateInfos']['RateInfo']['ChargeableRateInfo']['total']?></li>

                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><h6>CheckIn Instruction: </h6> <?php echo $model['HotelRoomAvailabilityResponse']['checkInInstructions']?></li>
                                    </ul>
                                    <?php if(isset($model['HotelRoomAvailabilityResponse']['specialCheckInInstructions'])){?>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><h6>Special CheckIn Instruction: </h6> <?php echo $model['HotelRoomAvailabilityResponse']['specialCheckInInstructions']?></li>
                                    </ul>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php } ?>
        </div>
    </div>

</div>
<?php $this->registerJs(
    "
   $(function() {
    setTimeout(function(){
        $('.grid').masonry({
             itemSelector: '.grid-item', // use a separate class for itemSelector, other than .col-
             percentPosition: true,
        });
    }, 3000);
});

"
);
?>