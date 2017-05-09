<?php


/* @var $this yii\web\View */

$this->title = 'Room Details';
$this->params['breadcrumbs'][] = $this->title;
$rooms_details =Yii::$app->session->get('room_type_data_available');
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
                <?php if (isset($model)) { ?>
                    <div id="products" class="row list-group grid">
                        <div class="item col-md-8 col-md-offset-2 grid-item" data-masonry='{ "itemSelector": ".grid-item"}'>
                            <div class="thumbnail">

                                <div class="caption">
                                    <p class="lead"><strong><?php echo $model['roomTypeDescription'];?></strong></p>

                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Request Rooms</strong> <?php echo $model['request_info']['single_room']+$model['request_info']['double_room']?> </li>
                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Check In Date</strong> : <?php echo $model['request_info']['arrivalDate']?></li>

                                        <li style="list-style: none">|</li>

                                        <li><strong>Check Out Date</strong> : <?php echo $model['request_info']['departureDate']?></li>

                                        <li style="list-style: none">
                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Total Rate</strong> : $<?php echo $model['RateInfos']['RateInfo']['ChargeableRateInfo']['total']?></li>
                                    </ul>
                                </div>

                                <div class="caption">
                                    <p class="lead text-center"><strong>All Charges</strong></p>
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <th>Average Base Rate</th>
                                            <td>$ <?php echo $model['RateInfos']['RateInfo']['ChargeableRateInfo']['averageBaseRate']?></td>
                                        </tr>
                                        <tr>
                                            <th>Average Rate</th>
                                            <td>$ <?php echo $model['RateInfos']['RateInfo']['ChargeableRateInfo']['averageRate']?></td>
                                        </tr>
                                        <tr>
                                            <th>Commissionable Usd Total</th>
                                            <td>$ <?php echo $model['RateInfos']['RateInfo']['ChargeableRateInfo']['commissionableUsdTotal']?></td>
                                        </tr>

                                        <tr>
                                            <th>Max Night Rate</th>
                                            <td>$ <?php echo $model['RateInfos']['RateInfo']['ChargeableRateInfo']['maxNightlyRate']?></td>
                                        </tr>
                                        <?php if(isset($model['RateInfos']['RateInfo']['HotelFees'])){
                                                if($model['RateInfos']['RateInfo']['HotelFees']['size'] == 1){
                                                    $fees =[$model['RateInfos']['RateInfo']['HotelFees']['HotelFee']];
                                                }else{
                                                    $fees = $model['RateInfos']['RateInfo']['HotelFees']['HotelFee'];
                                                }
                                            ?>
                                                <?php foreach ($fees as $fee):?>
                                                <tr>
                                                    <th>* Hotel Fee (<?=$fee['description']?>) Due at Hotel</th>
                                                    <td>$ <?php echo $fee['amount']?></td>
                                                </tr>
                                                <?php endforeach;?>
                                        <?php }?>
                                        </tbody>
                                    </table>
                                    <p class="lead text-center"><strong>Total Charges</strong></p>
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <th>Nightly Rate Total</th>
                                            <td>$ <?php echo $model['RateInfos']['RateInfo']['ChargeableRateInfo']['nightlyRateTotal']?></td>
                                        </tr>
                                        <?php
                                           // print_r($model['RateInfos']['RateInfo']['ChargeableRateInfo']['Surcharges']['size']);die;
                                                if($model['RateInfos']['RateInfo']['ChargeableRateInfo']['Surcharges']['size'] <= 1 ){
                                                     $tax_service = $model['RateInfos']['RateInfo']['ChargeableRateInfo']['Surcharges']['Surcharge']['amount'];
                                                }else{
                                                     $tax_service = $model['RateInfos']['RateInfo']['ChargeableRateInfo']['Surcharges']['Surcharge'][0]['amount'];
                                                     $sales_tax = $model['RateInfos']['RateInfo']['ChargeableRateInfo']['Surcharges']['Surcharge'][1]['amount'];
                                                }
                                        ?>
                                        <tr>
                                            <th>Surcharge (Tax And Service Fee)</th>
                                            <td>$ <?php echo $tax_service ?></td>
                                        </tr>
                                        <?php if(isset($sales_tax)):?>
                                        <tr>
                                            <th>Surcharge (Sales Tax)</th>
                                            <td>$ <?php echo $sales_tax;?></td>
                                        </tr>
                                        <?php endif;?>
                                        <tr style="background-color: rgba(0, 46, 91, 0.51);">
                                            <th>Total</th>
                                            <td>$ <?php echo $model['RateInfos']['RateInfo']['ChargeableRateInfo']['total']?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="caption">
                                    <p class="lead text-center"><strong>Nightly Charges</strong></p>
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <th>Nights</th>
                                            <th>Base Rate</th>
                                            <th>Rate</th>
                                        </tr>

                                        <?php if($model['RateInfos']['RateInfo']['ChargeableRateInfo']['NightlyRatesPerRoom']['size'] >= 1){?>
                                            <?php
                                            $i = 1;
                                            if($model['RateInfos']['RateInfo']['ChargeableRateInfo']['NightlyRatesPerRoom']['size'] == 1){
                                                $nights[0] = $model['RateInfos']['RateInfo']['ChargeableRateInfo']['NightlyRatesPerRoom']['NightlyRate'];
                                            }else{
                                                $nights = $model['RateInfos']['RateInfo']['ChargeableRateInfo']['NightlyRatesPerRoom']['NightlyRate'];
                                            }?>
                                            <?php foreach( $nights as $night ):?>

                                            <tr>
                                                <td>Nights <?=$i;?></td>
                                                <td>$ <?php echo $night['baseRate']?></td>
                                                <td>$ <?php echo $night['rate']?></td>
                                            </tr>
                                            <?php
                                            $i++;
                                            endforeach;?>
                                        <?php } ?>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <div class="item col-md-12">
                            <div class="col-md-6">
                                <span class="lead"><strong>Note</strong>: <?= $model['RateInfos']['RateInfo']['cancellationPolicy'] ?></span>
                            </div>
                            <div class="col-md-6">
                                <span class="lead"><strong>CheckIn Instructions</strong>: <?= $rooms_details['HotelRoomAvailabilityResponse']['checkInInstructions'];?></span>
                            </div>
                            <?php if(isset($rooms_details['HotelRoomAvailabilityResponse']['specialCheckInInstructions'])){?>
                                <div class="col-md-12">
                                    <span class="lead"><strong>CheckIn Instructions</strong>: <?= $rooms_details['HotelRoomAvailabilityResponse']['specialCheckInInstructions'];?></span>
                                </div>
                            <?php } ?>
                            </div>
                        <div class="item col-md-8 col-md-offset-2"> &nbsp;</div>
                        <div class="item col-md-8 col-md-offset-2">
                            <span class="lead"><a class="btn btn-lg btn-block btn-custom" href="<?= Yii::$app->urlManager->createAbsoluteUrl(['elite/feel-occupant-info','id'=>$model['hotelId']])?>" data-method="post">Fill Occupants Details</a></span>
                        </div>
                    </div>
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