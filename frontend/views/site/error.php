<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>

<div class="content_wrapper">
    <div class="page_404">
        <div class="bottom">
            <div class="container">
                <h1><?=$this->title?></h1>
            </div>
            <div class="bottom_wr">
                <div class="container">
                    <div class="media">
                        <div class="media-body media-middle">
                            <h3>The page you are looking for does not exist.</h3>
                        </div>
                        <div class="media-right media-middle">
                            <a href="http://www.crewfacilities.com/" class="button icon_right theme_style_3 bordered">
                                homepage								<i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--.content_wrapper-->
</div> <!--#wrapper-->
