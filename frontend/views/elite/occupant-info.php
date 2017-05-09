<?php


/* @var $this yii\web\View */

$this->title = 'Add Occupant Info';
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
            'method' => 'post',
            'options' => [
                'class' => 'form-horizontal',
                'data-toggle'=>"validator",
            ],

        ]); ?>


        <fieldset>
            <?php //echo '<pre>'.print_r($model,true).'</pre>';?>
            <?php
            $i = 1;
            if($model['BedTypes']['size'] == 1){
                $bed_types[0] = $model['BedTypes']['BedType'];
            }else{
                $bed_types = $model['BedTypes']['BedType'];
            }
            $bedTypesArr=[];
            foreach($bed_types as $b){
                $bedTypesArr=  \common\models\Reservations::array_push_assoc($bedTypesArr,$b['description'],$b['description']);
            }
            ?>
            <?php if(isset($model['request_info']['single_room']) && !empty($model['request_info']['single_room'])){?>
            <legend><strong>Single Rooms</strong></legend>
            <div class="col-md-12">
                <?php for($s=0;$model['request_info']['single_room']>$s;$s++){?>
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Room <?php echo $s+1;?></legend>
                            <hr>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">First Name</label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="occupant_info[single_room][<?=$s?>][0][first_name]" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Last Name</label>

                                <div class="col-lg-9">
                                    <input type="text" class="form-control" name="occupant_info[single_room][<?=$s?>][0][last_name]" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Bed Type</label>

                                <div class="col-lg-9">
                                    <?= \yii\bootstrap\Html::dropDownList("occupant_info[single_room][$s][bed_type]", null,
                                        $bedTypesArr) ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                <?php } ?>
            </div>
            <?php }?>
            <?php if(isset($model['request_info']['double_room']) && !empty($model['request_info']['double_room'])){ ?>

            <div class="col-md-12">
                <legend><strong>Double Rooms</strong></legend>
               <?php for($d=0;$model['request_info']['double_room']>$d;$d++){?>
                <div class="col-md-6">
                    <fieldset>
                        <legend>Room <?php echo $d+1;?></legend>
                        <hr>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">First Name</label>

                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="occupant_info[double_room][<?=$d?>][0][first_name]"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Last Name</label>

                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="occupant_info[double_room][<?=$d?>][0][last_name]"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Bed Type</label>

                            <div class="col-lg-9">
                                <?= \yii\bootstrap\Html::dropDownList("occupant_info[double_room][$d][bed_type]", null,
                                    $bedTypesArr) ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <?php } ?>
            </div>
            <?php }?>

        </fieldset>
        <br>

        <div class="form-group">
            <div class="col-lg col-lg-offset-2">
                <span class="lead">
                    <!--<a class="btn btn-lg btn-custom" href="<?/*= yii\helpers\Url::to(['site/select-card']) */?>" data-method="post">Select Payment Type</a></span>-->
                    <button class="btn btn-lg btn-custom" type="submit">Select Payment Type</button></span>
            </div>
        </div>
        <?php \yii\bootstrap\ActiveForm::end(); ?>

    </div>

</div>
