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
                <?php if (isset($model)) {
                    if($model['no_room'] == 1){
                        $hotels[0] = $model['HotelItineraryResponse']['Itinerary']['HotelConfirmation']['Hotel'];
                        $hotel_confirm[0] = $model['HotelItineraryResponse']['Itinerary']['HotelConfirmation'];
                    }else{
                        $hotels = $model['HotelItineraryResponse']['Itinerary']['HotelConfirmation'][0]['Hotel'];
                        $hotel_confirm = $model['HotelItineraryResponse']['Itinerary']['HotelConfirmation'];
                    }
                    ?>
                    <div id="products" class="row list-group grid">
                        <div class="item col-md-8 col-md-offset-2 grid-item" data-masonry='{ "itemSelector": ".grid-item"}'>
                            <div class="thumbnail">

                                <div class="caption">
                                    <p class="lead"><strong>Itinerary Details </strong></p>

                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Itinerary #</strong> <?= $model['HotelItineraryResponse']['Itinerary']['itineraryId']?></li> |
                                        <li><strong>Created On</strong> <?= $model['HotelItineraryResponse']['Itinerary']['creationDate']?> <?= $model['HotelItineraryResponse']['Itinerary']['creationTime']?></li>
                                        <li><strong>Status</strong> <?= \common\models\ExpediaIternary::getStatusName($hotel_confirm[0]['status'])?></li>
                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Check In Date</strong> <?= $model['HotelItineraryResponse']['Itinerary']['itineraryStartDate']?></li>

                                        <li style="list-style: none">|</li>

                                        <li><strong>Check Out Date</strong> <?= $model['HotelItineraryResponse']['Itinerary']['itineraryEndDate']?></li>

                                        <li style="list-style: none">

                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong># of Booked rooms</strong> : <?php echo $model['no_room']?></li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="caption">
                                    <p class="lead"><strong>Customer Info </strong></p>

                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Full Name  </strong> <?= $model['HotelItineraryResponse']['Itinerary']['Customer']['firstName']?> <?= $model['HotelItineraryResponse']['Itinerary']['Customer']['lastName']?></li>
                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Work Phone</strong> <?= $model['HotelItineraryResponse']['Itinerary']['Customer']['workPhone']?></li>

                                        <li style="list-style: none">|</li>

                                        <li><strong>Home Phone</strong> <?= $model['HotelItineraryResponse']['Itinerary']['Customer']['homePhone']?></li>

                                        <li style="list-style: none">
                                    </ul>
                                </div>
                                <hr>
                                <div class="caption">
                                    <p class="lead"><strong>Hotel Info </strong></p>
                                    <?php
                                    $confirmation_no = [];
                                    foreach($hotel_confirm as $hotel){
                                        array_push($confirmation_no,$hotel['confirmationNumber']);
                                    }

                                    ?>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Name :</strong> <?= $hotels[0]['name']?></li>

                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Address</strong> <?= $hotels[0]['address1']?>, <?= $hotels[0]['city']?> <?= isset($hotels[0]['postalCode'])?$hotels[0]['postalCode']:''?>, <?= isset($hotels[0]['stateProvinceCode'])?$hotels[0]['stateProvinceCode']:''?> <?= $hotels[0]['countryCode']?></li>
                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>Phone #</strong> <?= $hotels[0]['phone']?></li>
                                    </ul>
                                    <ul class="list-inline mrg-0 btm-mrg-10 clr-535353">
                                        <li><strong>FAX #</strong> <?= $hotels[0]['fax']?></li>
                                    </ul>
                                </div>


                            </div>
                        </div>
                        <div class="item col-md-8 col-md-offset-2">
                            <?php $form = \yii\bootstrap\ActiveForm::begin([
                                'method'=>'post',
                            ]); ?>
                            <span class="lead">
                                <input type="hidden" name="cancel[iternary_id]" value='<?php echo $model['HotelItineraryResponse']['Itinerary']['itineraryId'];?>'>
                                <input type="hidden" name="cancel[email]" value='<?php echo $model['HotelItineraryResponse']['Itinerary']['Customer']['email'];?>'>
                                <input type="hidden" name="cancel[confirmationNumber]" value='<?php echo \yii\helpers\Json::encode($confirmation_no);?>'>
                                <button type="submit" class="btn btn-lg btn-block btn-custom" data-method="post">Cancel Reservation</button>
                            </span>
                            <?php \yii\bootstrap\ActiveForm::end(); ?>

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