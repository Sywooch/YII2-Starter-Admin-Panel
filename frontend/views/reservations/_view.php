<?php
/**
 * Created by PhpStorm.
 * User: theta-php
 * Date: 11/1/17
 * Time: 9:15 PM
 */
use common\models\Reservations;

?>
<tr>
    <td><?= $model->reservation_no; ?></td>
    <td class="project_progress">
        <ul class="list-unstyled">
            <li>
                <small>Nights:</small>
                <strong><?php $date = yii::$app->commonfunction->createDateRangeArray($model->check_in_date, $model->check_out_date);
                    array_pop($date);
                    echo count($date); ?></strong></li>
            <li>
                <small>Single:</small>
                <strong><?= $model->single_rooms ?></strong></li>
            <li>
                <small>Double:</small>
                <strong><?= $model->double_rooms ?></strong></li>
        </ul>
    </td>
    <td class="project_progress">
        <?php if (!empty($model->geo_lat) && !empty($model->geo_long)) { ?>
            <span class="label label-primary">Geo Location Available</span>
        <?php } else { ?>
            <span><?= $model->street ?>, <?= $model->city ?></span>
            <small><?= $model->state ?>, <?= $model->zip ?></small>
        <?php } ?>
    </td>
    <td class="project_progress">
        <?php if(!empty($model->facilities_id)){?>
   <?= $model->facilities->name?></br>
    <small><?=$model->facilities->address." ".$model->facilities->city?></small>
    <small><?=$model->facilities->state." ".$model->facilities->zip?></small>
    <?php }else{?>
    <span class="badge">Not Available</span>
    <?php } ?>
    </td>
    <td>
        <?php if ($model->status == Reservations::PROCESSING) { ?>
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
        <?php } ?>


    <td>
        <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['reservations/view', 'id' => $model->id]) ?>"
           class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>
    </td>
</tr>
