<?php

$this->title = 'Facilities Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (isset($model['HotelInformationResponse']['hotelId'])) {
    $hotel = $model['HotelInformationResponse'];
    /*echo "<pre>";
    print_r($hotel);die;*/
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
            <div id="jssor_1"
                 style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 809px; height: 150px; overflow: hidden; visibility: hidden;">
                <!-- Loading Screen -->
                <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
                    <div
                        style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
                    <div
                        style="position:absolute;display:block;background:url('<?php Yii::$app->homeUrl; ?>img/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
                </div>
                <div data-u="slides"
                     style="cursor: default; position: relative; top: 0px; left: 0px; width: 809px; height: 150px; overflow: hidden;">
                    <?php foreach ($hotel['HotelImages']['HotelImage'] as $image): ?>
                        <div>
                            <img data-u="image" src="<?= $image['url'] ?>"/>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Arrow Navigator -->
                <span data-u="arrowleft" class="jssora03l" style="top:0px;left:8px;width:55px;height:55px;"
                      data-autocenter="2"></span>
                <span data-u="arrowright" class="jssora03r" style="top:0px;right:8px;width:55px;height:55px;"
                      data-autocenter="2"></span>
            </div>
            <p class="h2"><?= $hotel['HotelSummary']['name'] ?></p>

            <div class="media-body">
                <div class="col-md-6">
                    <p><strong>
                            <?= isset($hotel['HotelSummary']['address1']) ? $hotel['HotelSummary']['address1'] : 'N/a' ?>
                            , &nbsp;
                            <?= isset($hotel['HotelSummary']['city']) ? $hotel['HotelSummary']['city'] : 'N/a' ?>,
                            &nbsp;
                            <?= isset($hotel['HotelSummary']['stateProvinceCode']) ? $hotel['HotelSummary']['stateProvinceCode'] : 'N/a' ?>
                        </strong></p>
                    <?php echo \kartik\rating\StarRating::widget([
                        'name' => 'rating_35',
                        'value' => isset($hotel['HotelSummary']['hotelRating']) ? $hotel['HotelSummary']['hotelRating'] : 0,
                        'pluginOptions' => ['displayOnly' => true, 'size' => 'xs ',]
                    ]); ?>
                </div>
                <div class="col-md-6">
                    <span class="lead">
                        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['elite/room-availability','id'=>$model['HotelInformationResponse']['hotelId']]) ?>" data-method="post"
                                          class="btn btn-block btn-custom">Check Room Availability</a></span>
                </div>
            </div>
            <hr>
            <div class="container">
                <div id="products" class="row list-group grid">
                    <div class="item col-xs-6 col-lg-6 grid-item" data-masonry='{ "itemSelector": ".grid-item"}'>
                        <div class="thumbnail">
                            <div class="caption">
                                <p class="h3">Property Description</p>
                                <?php echo \yii\bootstrap\Html::decode(isset($hotel['HotelDetails']['propertyDescription']) ? $hotel['HotelDetails']['propertyDescription'] : 'N/a') ?>
                            </div>
                        </div>
                    </div>
                    <div class="item  col-xs-6 col-lg-6 grid-item">
                        <div class="thumbnail">
                            <div class="caption">
                                <p class="h3">Driving Directions</p>

                                <?php echo \yii\bootstrap\Html::decode(isset($hotel['HotelDetails']['drivingDirections']) ? $hotel['HotelDetails']['drivingDirections'] : 'N/a') ?>

                            </div>
                        </div>
                    </div>
                    <div class="item col-xs-6 col-lg-6 grid-item">
                        <div class="thumbnail">
                            <div class="caption">
                                <p class="h3">Area Information</p>

                                <?php echo \yii\bootstrap\Html::decode(isset($hotel['HotelDetails']['areaInformation']) ? $hotel['HotelDetails']['areaInformation'] : 'N/a') ?>

                            </div>
                        </div>
                    </div>
                    <div class="item  col-xs-6 col-lg-6 grid-item">
                        <div class="thumbnail">
                            <div class="caption">
                                <p class="h3">Room Information</p>

                                <?php echo \yii\bootstrap\Html::decode(isset($hotel['HotelDetails']['roomInformation']) ? $hotel['HotelDetails']['roomInformation'] : 'N/a') ?>

                            </div>
                        </div>
                    </div>
                    <div class="item  col-xs-6 col-lg-6 grid-item">
                        <div class="thumbnail">
                            <div class="caption">
                                <p class="h3">Hotel Policy</p>

                                <?php echo \yii\bootstrap\Html::decode(isset($hotel['HotelDetails']['hotelPolicy']) ? $hotel['HotelDetails']['hotelPolicy'] : 'N/a') ?>

                            </div>
                        </div>
                    </div>
                    <div class="item  col-xs-6 col-lg-6 grid-item">
                        <div class="thumbnail">
                            <div class="caption">
                                <p class="h3">Check In Information</p>

                                <?php echo \yii\bootstrap\Html::decode(isset($hotel['HotelDetails']['checkInInstructions']) ? $hotel['HotelDetails']['checkInInstructions'] : 'N/a') ?>

                            </div>
                        </div>
                    </div>
                    <div class="item  col-xs-6 col-lg-6 grid-item">
                        <div class="thumbnail">
                            <div class="caption">
                                <?php
                                //echo "<pre>";
                                //print_r($hotel['PropertyAmenities']);?>
                                <p class="h3">Amenlities</p>
                                <ul class="list-group">
                                    <?php foreach ($hotel['PropertyAmenities']['PropertyAmenity'] as $aminities): ?>
                                        <li class="list-group-item"><?php echo $aminities['amenity']; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item  col-xs-6 col-lg-6 grid-item">
                        <div class="thumbnail">
                            <div class="caption">
                                <p class="h3">Type Of Room</p>
                                <hr>
                                <ul class="list-group">
                                    <?php
                                        if($hotel['RoomTypes']['size'] == 1){
                                            $roomtypes = [$hotel['RoomTypes']['RoomType']];
                                        }else{
                                            $roomtypes = $hotel['RoomTypes']['RoomType'];
                                        }
                                    ?>
                                    <?php foreach ($roomtypes as $roomtype): ?>
                                        <li class="list-group-item"><?php echo $roomtype['description']; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    $this->registerJsFile(Yii::$app->request->BaseUrl . '/js/slider.js', ['depends' => [yii\web\JqueryAsset::className()]]);

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
<?php } ?>
