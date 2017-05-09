<?php


/* @var $this yii\web\View */

$this->title = 'Reservation';
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
    /*http://bootsnipp.com/snippets/featured/colourful-tabbed-slider-carousel*/
    .nav-justified > li > a
    {
        border-radius: 0px !important;
    }
</style>

<div class="container">

    <div class="col-md-12">
        <ul class="nav nav-pills col-md-6">
            <li class="active col-md-12 text-center"><a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('elite/search-hotel')?>"> <i class="fa fa-edit"></i> Reservation</a></li>
        </ul>
        <ul class="nav nav-pills col-md-6">
            <li class="col-md-12 text-center" style="background-color: #eee;"><a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('elite/requests')?>"><i class="fa fa-file-text"></i> My Requests</a></li>
        </ul>
    </div>
    <div class="tab-content" id="show">

        <div class="tab-pane active" id="address-tab">

            <a href="<?= yii\helpers\Url::to(['site/detail'])?>" data-method="post"  style="text-decoration: none; color:#000;">
                <div class="well" style="display: none; " id="hotel_result">
                    <div class="media">
                        <span class="pull-left" href="#">
                            <img class="media-object" src="http://placekitten.com/150/150">
                        </span>
                        <div class="media-body">
                            <p class="h4">Receta 1</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            <ul class="list-inline list-unstyled">
                                <li><span><i class="glyphicon glyphicon-calendar"></i> Minimum Rate: <b>85.05 </b></span></li>
                                <li>|</li>
                                <li>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star"></span>
                                    <span class="glyphicon glyphicon-star-empty"></span>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </a>

            <div class="row" id="site_location">
                <form id="accountForm" class="form-horizontal" style="margin-top: 20px;">

                    <p class="h3">Job Site Location</p>

                    <fieldset>
                        <legend><strong>Enter full address</strong></legend><hr>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">City</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="city" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">State</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="state" />
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend><strong>Reservation Detail</strong></legend><hr>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Check In Date</label>
                            <div class="col-lg-5">
                                <input type='date' class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Check Out Date</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="check_out_date" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">No.of Single Rooms</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="single_rooms" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">No.of Double Rooms</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="double_rooms" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-5 col-lg-offset-3">
                                <button type="button" id="hideq" class="btn btn-primary">Search Hotel</button>
                            </div>
                        </div>

                    </fieldset>
                </form>
            </div>
        </div>


        <div class="tab-pane" id="request-tab">
            <form id="accountForm" class="form-horizontal" style="margin-top: 20px;">
                <div class="form-group">
                    <label class="col-lg-3 control-label">Createion Start Date</label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" name="address" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">Creation End Date</label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" name="city" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-5 col-lg-offset-3">
                        <button type="submit" class="btn btn-primary">Select Date Range</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



