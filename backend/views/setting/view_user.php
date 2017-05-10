<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 10/5/17
 * Time: 11:30 PM
 */
?>
<div class="container profile-layout-container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-profile">
                <div class="panel-profile-header">
                    <div class="image-upload-container" style="width: 100%; height: 100%; overflow:hidden;">
                        <!-- profile image output-->
                        <img class="img-profile-header-background" id="user-banner-image"
                             src="<?= Yii::$app->homeUrl?>/default_image/default_banner.png" width="100%" style="width: 100%; max-height: 192px;">
                        <!-- check if the current user is the profile owner and can change the images -->
                        <div class="image-upload-loader" id="banner-image-upload-loader" style="padding: 50px 350px;">
                            <div class="progress image-upload-progess-bar" id="banner-image-upload-bar">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="00"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                </div>
                            </div>
                        </div>
                        <!-- show user name and title -->
                        <div class="img-profile-data">
                            <h1><?= $model->profile->first_name . " " . $model->profile->last_name; ?></h1>

                            <h2><?= $model->role->role_name;?></h2>
                        </div>
                    </div>
                    <div class="image-upload-container profile-user-photo-container"
                         style="width: 140px; height: 140px;">
                        <a href="javascript:void(0);">
                            <img class="img-rounded profile-user-photo" id="user-profile-image"
                                 src="<?=DEFAULT_IMAGE;?>"
                                 data-src="holder.js/140x140" alt="140x140" style="width: 140px; height: 140px;">
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="panel-profile-controls">
                        <!-- start: User statistics -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="controls controls-header pull-right">
                                    <a class="btn btn-primary edit-account" href="javascript:"><i class="fa fa-edit"></i> Edit account</a>
                                    <a class="btn btn-warning edit-account" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('setting/index')?>"><i class="fa fa-hand-o-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 layout-nav-container">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Profile</strong> menu</div>
                <div class="list-group">
                    <a class="list-group-item active" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('setting/view-user/'.$model->id)?>"><span>About</span></a>
                    <a class="list-group-item" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('setting/change-user-password/'.$model->id)?>"><span>Change Password</span></a>
                </div>
            </div>
        </div>

        <div class="col-md-7 layout-content-container">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>About</strong> this user</div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="profile-category-1">
                            <form role="form">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><i class="fa fa-user"></i> Full Name </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?= $model->profile->full_name ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><i class="fa fa-envelope"></i> Email </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?= $model->email ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Gender </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php
                                            if ($model->profile->gender != NULL && !empty($model->profile->gender)) {
                                                if($model->profile->gender == MALE){
                                                    echo "<i class=\"fa fa-male\" aria-hidden=\"true\"></i> Male";
                                                }else{
                                                    echo "<i class=\"fa fa-female\" aria-hidden=\"true\"></i> Female";
                                                }
                                            } else {
                                                echo "Not Specified";
                                            } ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Status </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php if ($model->status == ACTIVE) {
                                                echo "<i class=\"fa fa-check\" aria-hidden=\"true\"></i>";
                                            } else {
                                                echo "<i class=\"fa fa-times\" aria-hidden=\"true\"></i>";
                                            } ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><i class="fa fa-info-circle"></i> Title </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?= $model->role->role_name ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><i class="fa fa-calendar"></i> Date Of Joining </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo date('d-M-Y', $model->created_at) ?>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 layout-content-container">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Reservation </strong> assign</div>
                <div class="panel-body">
                    <div class="knob-container" style="text-align: center;opacity: 1;">
                        <strong>Since </strong> <?= date('d-M-Y', $model->created_at) ?> <br><br>

                        <div style="display:inline;width:120px;height:140px;"><canvas width="120" height="140"></canvas><input id="user-total" class="knob" data-width="120" data-height="140" data-displayprevious="true" data-readonly="true" data-fgcolor="#708fa0" data-skin="tron" data-thickness=".2" value="4" data-max="4" style="font-size: 30px; margin-top: 40px; width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-left: -92px; border: 0px; background: none; font-style: normal; font-variant: normal; font-weight: bold; font-stretch: normal; line-height: normal; font-family: Arial; text-align: center; color: rgb(112, 143, 160); padding: 0px; -webkit-appearance: none;" readonly="readonly"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
