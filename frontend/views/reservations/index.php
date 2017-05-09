<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/1/17
 * Time: 4:00 PM
 */
$this->title = 'Reservation Request';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="col-md-12">
        <div class="thumbnail">

            <p class="lead"><strong>Reservation Filter</strong></p><hr>

            <?php $form = \yii\bootstrap\ActiveForm::begin(['method' => 'get']); ?>
                <div class="row">
                    <div class="col-md-4 sep-top-xs">
                        <div class="form-group">
                            <label for="name" class="upper">Reservaton#</label>
                            <?= $form->field($searchModel, 'reservation_no')->textInput(['placeholder' => "i.e, NOV2416142129", 'class' => 'form-control'])->label(false); ?>

                        </div>
                    </div>
                    <div class="col-md-4 sep-top-xs">
                        <div class="form-group">
                            <label for="name" class="upper">Job#</label>
                            <?= $form->field($searchModel, 'job_no')->textInput(['placeholder' => "i.e, JOB123.", 'class' => 'form-control  required'])->label(false); ?>

                        </div>
                    </div>
                    <div class="col-md-4 sep-top-xs">
                        <div class="form-group">
                            <label for="name" class="upper">PO#</label>
                            <?= $form->field($searchModel, 'po_no')->textInput(['placeholder' => "i.e, PO123.", 'class' => 'form-control  required'])->label(false); ?>

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Apply</button>
                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('reservations')?>" class="btn btn-default">Reset</a>
                </div>
            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
    </div>
    <div class="col-md-12" style="margin-bottom: 10px;">
        <span><a href="https://www.crewfacilities.com/room-request/" class="btn btn-small btn-primary"><i class="fa fa-plus"></i> New Reservation Request </a></span>
    </div>
    <div class="col-md-12">

        <div class="thumbnail">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Request Summary</th>
                    <th>Request Location</th>
                    <th>Facilities info</th>
                    <th>Status</th>
                    <th>#Action</th>
                </tr>
                </thead>
                <tbody>
                <?php \yii\widgets\Pjax::begin(['id' => 'resevation-board']) ?>
                <?=
                \yii\widgets\ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_view',
                    'options' => [
                        'tag' => 'tbody',
                        'data-pjax' => true
                    ],
                ]);
                ?>
                <?php \yii\widgets\Pjax::end() ?>
                </tbody>
            </table>
        </div>
    </span>
</div>


