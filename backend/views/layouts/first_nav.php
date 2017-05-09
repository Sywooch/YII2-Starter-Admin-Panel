<div id="topbar-first" class="topbar">
    <div class="container">
        <div class="topbar-brand hidden-xs">
            <a class="navbar-brand" href="<?= Yii::$app->homeUrl; ?>" id="text-logo"><?= APP_NAME; ?> </a>
        </div>
        <div class="topbar-actions pull-right">
            <ul class="nav">
                <li class="dropdown account">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <div class="user-title pull-left hidden-xs">
                            <strong><?php echo Yii::$app->user->identity->first_name; ?></strong><br/><span
                                class="truncate"><?php echo Yii::$app->user->identity->role->role_name; ?></span>
                        </div>

                        <img id="user-account-image" class="img-rounded"
                             src="<?= Yii::$app->homeUrl; ?>/default_image/default_user.jpg"
                             height="32" width="32" alt="32x32" data-src="holder.js/32x32"
                             style="width: 32px; height: 32px;"/>

                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('user/profile') ?>">
                                <i class="fa fa-user"></i> My profile </a>
                        </li>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('setting') ?>">
                                <i class="fa fa-edit"></i> Account settings </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('site/logout') ?>"
                               data-confirm="Want to logout?" data-method="post">
                                <i class="fa fa-sign-out"></i> Logout

                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="notifications pull-right">

            <div class="btn-group">
                <a href="#" id="icon-notifications" data-toggle="dropdown">
                    <i class="fa fa-bell animated swing"></i>
                </a>
                <span id="badge-notifications" style="display:none;"
                      class="label label-danger label-notifications">1</span>

                <!-- container for ajax response -->
                <ul id="dropdown-notifications" class="dropdown-menu">
                    <li class="dropdown-header">
                        <div class="arrow"></div>
                        Notifications
                        <div class="dropdown-header-link"><a id="mark-seen-link"
                                                             href="javascript:markNotificationsAsSeen();">Mark all as
                                seen</a>
                        </div>
                    </li>
                    <ul class="media-list">
                        <li class="new">
                            <a href="http://theta123.humhub.com/notification/entry?id=2">
                                <div class="media">

                                    <!-- show user image -->
                                    <img class="media-object img-rounded pull-left" data-src="holder.js/32x32"
                                         alt="32x32" style="width: 32px; height: 32px;"
                                         src="http://localhost/humhub/img/default_user.jpg">

                                    <!-- show space image -->

                                    <!-- show content -->
                                    <div class="media-body">

                                        <strong>Sara Schuster</strong> and <strong>David Roberts</strong> commented post
                                        "We're looking for great slogans of famous brands. Maybe...".
                                        <br> <span class="time" title="Jan 18, 2017 - 7:48 AM">2 minutes ago</span>
                                        <span class="label label-danger">New</span></div>

                                </div>
                            </a>
                        </li>
                    </ul>
                    <li>
                        <div class="dropdown-footer">
                            <a class="btn btn-default col-md-12" href="/humhub/index.php?r=notification%2Foverview">
                                Show all notifications </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>

</div>