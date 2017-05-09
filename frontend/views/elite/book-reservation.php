<?php


/* @var $this yii\web\View */

$this->title = 'Book Reservation';
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

        <fieldset>
            <legend><strong>Reservation Details</strong></legend>
            <hr>

            <div class="form-group row">
                <label class="col-lg-3 control-label">First Name</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'first_name')->textInput(['autofocus' => true,'class'=>'form-control','placeholder'=>'i.e John'])->label(false) ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">Last Name</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'last_name')->textInput(['class'=>'form-control','placeholder'=>'i.e Doe'])->label(false); ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">Email</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'email')->textInput(['class'=>'form-control','placeholder'=>'i.e info@company.com'])->label(false); ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">Home Phone</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'home_phone')->textInput(['class'=>'form-control','placeholder'=>'i.e 123 123 1234'])->label(false); ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">Work Phone</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'work_phone')->textInput(['class'=>'form-control','placeholder'=>'i.e 123 123 1234'])->label(false); ?>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend><strong>Credit Card Information</strong></legend>
            <hr>

            <div class="form-group row">
                <label class="col-lg-3 control-label">Credit Card Type</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'credit_card_type_disp')->textInput(['class'=>'form-control','disabled'=>'disabled'])->label(false) ?>
                    <?= $form->field($model, 'credit_card_type')->hiddenInput(['class'=>'form-control'])->label(false) ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">Credit Card Number</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'credit_card_number')->textInput(['class'=>'form-control','placeholder'=>'i.e 1234 1234 1234 1234'])->label(false) ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">CVV</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'credit_card_identifier')->textInput(['class'=>'form-control','placeholder'=>'i.e 123'])->label(false) ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">Credit Card Expiration Month</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'credit_card_exp_month')->textInput(['class'=>'form-control','placeholder'=>'i.e 02'])->label(false) ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">Credit Card Expiration Year</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'credit_card_exp_year')->textInput(['class'=>'form-control','placeholder'=>'i.e 2020'])->label(false) ?>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend><strong>Address Information</strong></legend>
            <hr>
            <div class="form-group row">
                <label class="col-lg-3 control-label">Address</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'address')->textInput(['class'=>'form-control','placeholder'=>'i.e 12th St. Shine Block'])->label(false) ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">City</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'city')->textInput(['class'=>'form-control','placeholder'=>'i.e New your city'])->label(false) ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">State</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'state')->textInput(['class'=>'form-control','placeholder'=>'i.e NY'])->label(false) ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">Country</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'country')->textInput(['class'=>'form-control','placeholder'=>'i.e US'])->label(false) ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 control-label">Postal Code</label>

                <div class="col-lg-5">
                    <?= $form->field($model, 'postal_code')->textInput(['class'=>'form-control','placeholder'=>'i.e 10001'])->label(false) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-5 col-lg-offset-3">
                    <?= \yii\bootstrap\Html::submitButton('Book Reservation', ['class' => 'btn btn-primary', 'name' => 'reservation-button']) ?>
                </div>
            </div>
        </fieldset>

        <?php \yii\bootstrap\ActiveForm::end(); ?>
    </div>

</div>
