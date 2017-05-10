<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 17/6/16
 * Time: 12:17 AM
 */ ?>
<div class="container profile-layout-container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-profile">
                <div class="panel-profile-header">
                    <div class="image-upload-container" style="width: 100%; height: 100%; overflow:hidden;">
                        <!-- profile image output-->
                        <img class="img-profile-header-background" id="user-banner-image"
                             src="<?= Yii::$app->homeUrl?>default_image/default_banner.png" width="100%" style="width: 100%; max-height: 192px;">
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
                            <h1><?= \Yii::$app->user->identity->first_name . " " . \Yii::$app->user->identity->last_name; ?></h1>
                            <h2><?= \Yii::$app->user->identity->role->role_name;?></h2>
                        </div>
                    </div>
                    <div class="image-upload-container profile-user-photo-container"
                         style="width: 140px; height: 140px;">
                        <a href="javascript:void(0);">
                            <img class="img-rounded profile-user-photo" id="user-profile-image"
                                 src="<?=Yii::$app->commonfunction->getProfilePic();?>"
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
                                    <a class="btn btn-primary edit-account"
                                       href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('user/edit-profile')?>"><i class="fa fa-edit"></i> Edit account</a></div>
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
                    <a class=" active list-group-item"
                       href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('user/profile')?>"><span>About</span></a>
                </div>
            </div>
        </div>

        <div class="col-md-10 layout-content-container">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>About</strong> this user</div>
                <div class="panel-body">
                    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                        <li class="active">
                            <a href="#profile-category-1" data-toggle="tab">General</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="profile-category-1">
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">
                                        First name </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo Yii::$app->user->identity->first_name?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">
                                        Last name </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo Yii::$app->user->identity->last_name?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">
                                        Username </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo Yii::$app->user->identity->username?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">
                                        Email </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo Yii::$app->user->identity->email?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">
                                        Title </label>
                                    <div class="col-sm-9">
                                        <p class="form-control-static"><?php echo Yii::$app->user->identity->role->role_name?></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
