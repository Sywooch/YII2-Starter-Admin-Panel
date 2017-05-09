<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body  class="page page-id-989 page-template-default  header_style_2 wpb-js-composer js-comp-ver-4.11.2 vc_responsive">
<?php $this->beginBody() ?>
<div id="wrapper">
    <div class="content_wrapper">
        <header id="header">
            <div class="top_bar">
                <div class="container">
                    <div class="top_bar_info_wr">
                        <div class="top_bar_info_switcher">
                            <div class="active">
                                <span>Central US Office:</span>
                            </div>
                            <ul style="display: none;">
                                <li>
                                    <a href="#top_bar_info_1">Central US Office:</a>
                                </li>
                                <li>
                                    <a href="#top_bar_info_2">Southeast US Office</a>
                                </li>
                            </ul>
                        </div>
                        <ul class="top_bar_info" id="top_bar_info_1" style="display: block;">
                            <li>
                                <i class="stm-marker"></i>
                                <span>311 RR 620  Suite 107 Austin, TX 78734</span>
                            </li>
                        </ul>
                        <ul class="top_bar_info" id="top_bar_info_2">
                            <li>
                                <i class="stm-marker"></i>
                                <span>2501 East Commercial Blvd. Suite 203 Fort Lauderdale, FL 33308</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="header_top clearfix affix-top">
                <div class="container">
                    <div class="logo media-left media-middle">
                        <a href="http://www.crewfacilities.com/"><img src="<?php echo yii::$app->homeUrl;?>img/crewfacilities_logo_2X-1.png"
                                                                      style="width: px; height: 120px;"
                                                                      alt="Crew Facilities"></a>
                    </div>


                    <?php echo $this->render('main_menu');?>


                </div>
            </div>

            <div class="mobile_header">
                <div class="logo_wrapper clearfix">
                    <div class="logo">
                        <a href="http://www.crewfacilities.com/"><img src="<?php echo yii::$app->homeUrl;?>img/crewfacilities_logo_2X-1.png"
                                                                      style="width: px; height: 120px;"
                                                                      alt="Crew Facilities"></a>
                    </div>
                    <div id="menu_toggle">
                        <button></button>
                    </div>
                </div>
                <div class="header_info">
                    <div class="top_nav_mobile">
                        <ul id="menu-main-menu-1" class="main_menu_nav">
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-997"><a
                                        href="http://www.crewfacilities.com/">Home</a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-1003">
                                <a href="http://www.crewfacilities.com/solutions/">Solutions</a><span
                                        class="arrow"><i></i></span>
                                <ul class="sub-menu">
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-999">
                                        <a href="http://www.crewfacilities.com/corporate-lodging-solution/">Corporate
                                            Lodging Solution</a></li>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1000">
                                        <a href="http://www.crewfacilities.com/employee-travel-policy/">Employee Travel
                                            Policy</a></li>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1074">
                                        <a href="http://www.crewfacilities.com/per-diem-travel/">Per Diem Travel</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-1078">
                                <a href="http://www.crewfacilities.com/about-us/">About Us</a><span
                                        class="arrow"><i></i></span>
                                <ul class="sub-menu">
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1172">
                                        <a href="http://www.crewfacilities.com/about-our-team/">About Our Team</a></li>
                                </ul>
                            </li>
                            <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1075"><a
                                        href="http://www.crewfacilities.com/news/">News</a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-989 current_page_item menu-item-1002">
                                <a href="http://www.crewfacilities.com/room-request/">Room Request</a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-998"><a
                                        href="http://www.crewfacilities.com/contact-us/">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="icon_texts">
                    </div>
                </div>
            </div>
        </header>

        <div id="main">
            <div class="page_title">
                <div class="container">
                    <?php  echo Breadcrumbs::widget([
                        'tag'=>'div',
                        'options'=>['class' => 'breadcrumbs'],
                        'itemTemplate' => " <span typeof='v:Breadcrumb'>{link}</span> <span><i class=\"fa fa-angle-right\"></i></span> \n", // template for all links
                        'activeItemTemplate' => "<span typeof='ListItem'><a>{link}</a></span>\n", // template for all links
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]);

                    ?>


                    <h1 class="h2"><?= Html::encode($this->title) ?>
                        <?php if( !Yii::$app->user->isGuest){?>
                        <?php if(Yii::$app->controller->id != 'client' ){?>
                        <small class="lead pull-right" ><a href="<?php echo Yii::$app->homeUrl;?>" style="color: #000; text-decoration: none;"><i class="fa fa-home"></i> Choose services</a></small>
                        <?php } }?>

                    </h1>

                </div>
            </div>
            <div class="container">
                <div class="content-area">
                    <?php echo \Yii::$app->view->renderFile('@frontend/views/layouts/message_panel.php'); ?>
                    <?= $content ?>
                </div>
            </div>
        </div> <!--#main-->

    </div> <!--.content_wrapper-->


    <footer id="footer" class="footer style_1">
        <div class="widgets_row">
            <div class="container">
                <div class="footer_widgets">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="footer_logo">
                                <a href="http://www.crewfacilities.com/">
                                    <img src="<?=Yii::$app->homeUrl;?>/img/logo_default.svg" alt="Crew Facilities" />
                                </a>
                            </div>
                            <section id="text-4" class="widget widget_text"><h4 class="widget_title no_stripe">
                                    CREWFACILITIES OFFICES</h4>
                                <div class="textwidget"><b>CENTRAL US OFFICE:</b>
                                    <ul>
                                        <li><p><i class="stm-location-2"></i> 311 RR 620
                                                <br>
                                                Suite 107<br>
                                                Austin, TX 78734</p></li>
                                    </ul>


                                    <b>SOUTHEAST US OFFICE</b>

                                    <ul>
                                        <li><p><i class="stm-location-2"></i> 2501 East Commercial Blvd.<br>
                                                Suite 203<br>
                                                Fort Lauderdale, FL 33308</p></li>
                                    </ul>
                                </div>
                            </section>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <section id="text-3" class="widget widget_text"><h4 class="widget_title no_stripe">GET IN
                                    TOUCH</h4>
                                <div class="textwidget"><h4 style="color:#fff;"><i class="fa fa-phone"></i> VOICE &nbsp;&nbsp;
                                        &nbsp; <a style="color:#fff;" href="callto://800.273.9256">800.273.9256 </a> <br>
                                        FACSIMILE &nbsp; 866.719.9092 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</h4>

                                    <h4 style="color:#fff;"><i class="fa fa-envelope"></i> TEXT : 512-368-2280 </h4>
                                </div>
                            </section>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <section id="text-5" class="widget widget_text"><h4 class="widget_title no_stripe">WHO WE
                                    ARE</h4>
                                <div class="textwidget"><img src="<?=Yii::$app->homeUrl;?>img/logo_white_footer.png" alt="Relevant textual alternative to the image" />
                                    <iframe width="100%" height="170" src="https://www.youtube.com/embed/3-2n_mqlxi8" allowfullscreen=""></iframe>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright_row">
            <div class="container">
                <div class="copyright_row_wr">
                    <div class="copyright">
                        Copyright © 2012-2015 crewfacilities.com All rights reserved
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div> <!--#wrapper-->


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
