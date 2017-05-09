<?php


/* @var $this yii\web\View */

$this->title = 'Elite Reservation';
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
        <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'search-form',
        ]); ?>

            <p class="h3">Job Site Location</p>

            <fieldset>
                <legend><strong>Enter full address</strong></legend>
                <hr>

                <div class="form-group row">
                    <label class="col-lg-3 control-label">City</label>

                    <div class="col-lg-5">
                        <?= $form->field($model, 'city')->textInput(['autofocus' => true,'class'=>'form-control','placeholder'=>'i.e Austin'])->label(false) ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 control-label">State</label>

                    <div class="col-lg-5">
                        <?= $form->field($model, 'state')->textInput(['class'=>'form-control','placeholder'=>'i.e TX'])->label(false); ?>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend><strong>Reservation Detail</strong></legend>
                <hr>

                <div class="form-group row">
                    <label class="col-lg-3 control-label">Check In Date</label>

                    <div class="col-lg-5">
                        <?php echo \kartik\date\DatePicker::widget([
                            'name' => 'JobSiteForm[check_in_date]',
                            'options'=>[
                                'placeholder'=>'i.e 10/01/2017',
                            ],
                            'type' => \kartik\date\DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose'=>true,
                                'format' => 'mm/dd/yyyy'
                            ]
                        ]);?>
                    </div>
                    <div class="col-lg-4"></div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 control-label">Check Out Date</label>

                    <div class="col-lg-5">
                        <?php echo \kartik\date\DatePicker::widget([
                            'name' => 'JobSiteForm[check_out_date]',
                            'options'=>[
                                'placeholder'=>'i.e 10/01/2017',
                            ],
                            'type' => \kartik\date\DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose'=>true,
                                'format' => 'mm/dd/yyyy'
                            ]
                        ]);?>                    </div>
                    <div class="col-lg-4"></div>

                </div>
                <div class="form-group row">
                    <label class="col-lg-3 control-label">No.of Single Rooms</label>

                    <div class="col-lg-5">
                        <?= $form->field($model, 'single_rooms')->textInput(['class'=>'form-control','placeholder'=>'i.e 4'])->label(false) ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 control-label">No.of Double Rooms</label>

                    <div class="col-lg-5">
                        <?= $form->field($model, 'double_rooms')->textInput(['class'=>'form-control','placeholder'=>'i.e 4'])->label(false) ?>
                    </div>
                </div>
                <div class="form-group row">

                    <div class="col-lg-5 col-lg-offset-3">
                        <div><a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/terms-and-conditions') ?>">Terms & Conditions</a> is agreed</div>
                        <div>&nbsp;</div>
                        <?= \yii\bootstrap\Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>
                </div>

            </fieldset>
        <?php \yii\bootstrap\ActiveForm::end(); ?>
    </div>

</div>
