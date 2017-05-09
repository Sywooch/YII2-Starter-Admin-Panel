<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/1/17
 * Time: 1:56 PM
 */

use common\models\Reservations;
$this->title = 'Reservation Detail';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">

    <div class="col-md-12">
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="thumbnail">
                    <div class="caption">
                        <p class="lead">
                            <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('reservations/index')?>" class="btn btn-primary btn-md pull-right"><i class="fa fa-hand-o-left"> </i> Back to Reservations</a>
                            <strong>Reservation Info </strong> <?php if ($model->status == Reservations::PROCESSING) { ?>
                                <button type="button"
                                        ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status') ?>"
                                        reservation="<?= $model->id; ?>" class="btn btn-warning btn-xs status-btn">in-progress
                                </button>
                            <?php } elseif ($model->status == Reservations::WAITING && $model->hotel_confirmed == ACTIVE) { ?>
                                <button type="button"
                                        ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status') ?>"
                                        reservation="<?= $model->id; ?>" class="btn btn-default btn-xs status-btn">Waiting on Client
                                    confirmation
                                </button>
                            <?php } elseif ($model->status == Reservations::WAITING && $model->hotel_confirmed == INACTIVE) { ?>
                                <button type="button"
                                        ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status') ?>"
                                        reservation="<?= $model->id; ?>" class="btn btn-default btn-xs status-btn">Waiting on Facility
                                    confirmation
                                </button>
                            <?php } elseif ($model->status == Reservations::CANCEL_BY_CLIENT) { ?>
                                <button type="button"
                                        ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status') ?>"
                                        reservation="<?= $model->id; ?>" class="btn btn-danger btn-xs status-btn">Cancelled By client
                                </button>
                            <?php } elseif ($model->status == Reservations::CANCEL_BY_CREWFACTS) { ?>
                                <button type="button"
                                        ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status') ?>"
                                        reservation="<?= $model->id; ?>" class="btn btn-danger btn-xs status-btn">Cancelled By crewfacts
                                </button>
                            <?php } elseif ($model->status == Reservations::PENDING_CHECK_IN && $model->check_in_date == date('Y-m-d')) { ?>
                                <button type="button"
                                        ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status') ?>"
                                        reservation="<?= $model->id; ?>" class="btn btn-primary btn-xs status-btn">Check in Tonight
                                </button>
                            <?php } elseif ($model->status == Reservations::PENDING_CHECK_IN) { ?>
                                <button type="button"
                                        ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status') ?>"
                                        reservation="<?= $model->id; ?>" class="btn btn-primary btn-xs status-btn">Future Check in
                                </button>
                            <?php } elseif ($model->status == Reservations::CHECKED_IN && $model->check_out_date == date('Y-m-d')) { ?>
                                <button type="button"
                                        ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status') ?>"
                                        reservation="<?= $model->id; ?>" class="btn btn-success btn-xs status-btn">Checked Out Tonight
                                </button>
                            <?php } elseif ($model->status == Reservations::CHECKED_IN) { ?>
                                <button type="button"
                                        ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status') ?>"
                                        reservation="<?= $model->id; ?>" class="btn btn-success btn-xs status-btn">Checked in
                                </button>
                            <?php } elseif ($model->status == Reservations::EXTEND) { ?>
                                <button type="button"
                                        ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status') ?>"
                                        reservation="<?= $model->id; ?>" class="btn btn-warning btn-xs status-btn">Extend request
                                </button>
                            <?php } elseif ($model->status == Reservations::CHECK_OUT) { ?>
                                <button type="button"
                                        ajax-url="<?= Yii::$app->urlManager->createAbsoluteUrl('reservation/change-status') ?>"
                                        reservation="<?= $model->id; ?>" class="btn btn-success btn-xs status-btn">Checked Out
                                </button>
                            <?php } ?></p>
                        <div class="col-xs"><?=$model->reservation_no;?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-4">
                <div class="thumbnail">
                    <div class="caption">
                        <?php if($model->facilities_id) { ?>
                        <p class="lead"><strong><?= $model->facilities->name;?></strong></p>
                        <div class="col-xs">
                            <ul style="list-style:none;">
                                <li><strong>Email : </strong><?= $model->facilities->email;?></li>
                                <li><strong>phone#: : </strong><?= isset($model->facilities->phone_no)?$model->facilities->phone_no:'n/a';?></li>
                                <li><strong>Fax# : </strong><?= isset($model->facilities->fax)?$model->facilities->fax:'n/a';?></li>
                            </ul>
                        </div><hr>
                        <div class="col-xs">
                            <ul style="list-style: none;">
                                <li><?php echo $model->facilities->address; ?> <?php echo $model->facilities->city . " " . $model->facilities->state . " - " . $model->facilities->zip; ?></li>
                                <li>Booking : <strong><?php echo $model->total_room; ?> Rooms</strong></li>
                                <li>Arrival Time : <strong><?php echo date('h:i A', strtotime($model->arrival_time)); ?></strong></li>
                            </ul>
                        </div>
                        <?php } else{ ?>
                            <p class="lead">Facility Not Assigned </p>
                        <?php } ?>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="thumbnail">
                    <div class="caption">
                        <p class="lead"><strong>Reservation info</strong></p>
                        <div class="col-xs">
                            <ul style="list-style: none;">
                                <li><strong>Reservation# : </strong><?=$model->reservation_no;?>    </li>
                                <li><strong>Confirmation# : </strong><?php if(isset($model->confirmation_no)){ echo $model->confirmation_no; }else{ ?> N/A <?php } ?></li>
                                <li><strong>Ordered By : </strong><?= ($model->requested_by) ? $model->creator->profile->full_name : "N/A" ?></li>
                                <li><strong>Created At : </strong><?= date('dS M y h:i A', $model->created_date); ?></li>
                            </ul>
                        </div><hr>
                        <p class="lead"><strong>Lead Person info</strong></p>
                        <div class="col-xs">
                            <ul style="list-style: none;">
                                <li><strong>Name : </strong><?= ($model->contact_person_name) ? $model->contact_person_name : "Not Available" ?> <!--<a class="label label-primary updateemail" href="#"><i class="fa fa-pencil"></i> update</a>--></li>
                                <li><strong>Email : </strong><?= ($model->contact_person_email) ? $model->contact_person_email : "Not Available" ?><!--<a class="label label-primary updateemail" href="#"><i class="fa fa-pencil"></i> update</a>--></li>
                                <li><strong>Phone : </strong><?= ($model->contact_person_phone) ? $model->contact_person_phone : "Not Available" ?> <!--<a class="label label-primary updateemail" href="#"><i class="fa fa-pencil"></i> update</a>--></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="thumbnail">
                    <div class="caption">
                        <p class="lead"><strong>Request Summary</strong></p>
                        <div class="col-xs">
                            <ul style="list-style: none;">
                                <li><strong>Check In Date : </strong><?= date('m/d/Y',strtotime($model->check_in_date));?></li>
                                <li><strong>Check Out Date : </strong><?= date('m/d/Y',strtotime($model->check_out_date));?></li>
                                <li><strong>Total Nights : </strong><?php $date=yii::$app->commonfunction->createDateRangeArray($model->check_in_date, $model->check_out_date); array_pop($date); echo count($date); ?></li>
                                <li><strong>Single Rooms : </strong> <?= $model->single_rooms ?></li>
                                <li><strong>Double Rooms : </strong> <?= $model->double_rooms ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-12">
                <div class="thumbnail">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Room#</th>
                            <th>Guest Name</th>
                            <th>Type</th>
                            <th>Check In Date</th>
                            <th>Check Out Date</th>
                            <th>Rate</th>
                            <th>Tax</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($model->reservationRooms as $room):?>
                            <?php
                            $guests = [];
                            foreach($room->reservationGuests as $guest){
                                array_push($guests, $guest->person->first_name.' '.$guest->person->last_name);
                            } ;?>
                            <tr>
                                <td><?php echo isset($room->reservationRoomData->room_no)?$room->reservationRoomData->room_no:'N/A';?></td>
                                <td><?php echo implode(', ',$guests);?></td>
                                <td><?php echo ($room->room_type == \common\models\ReservationRooms::DOUBLEROOM)?"Double":'Single';?></td>
                                <td><?= date('m/d/Y',strtotime($room->check_in_date));?></td>
                                <td><?= date('m/d/Y',strtotime($room->check_out_date));?></td>
                                <td><?php echo isset($room->reservationRoomData->sell_rate)?'$ '.$room->reservationRoomData->sell_rate:'N/A';?></td>
                                <td><?php echo isset($room->reservationRoomData->tax)? ($room->reservationRoomData->tax_type == 0)?$room->reservationRoomData->tax.'%':'$ '.$room->reservationRoomData->tax:'N/A';?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-12">
                <div class="thumbnail">
                    <div class="caption">
                        <p class="lead"><strong><?php echo $model->client->name;?></strong></p><hr>
                        <div class="col-xs text-center">
                            <p style="font-size:x-large">Invoice to Client : <strong><?php echo Yii::$app->commonfunction->calculateInvoiceAmt($model->id) ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
