<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';


    public $css = [
        'css/styles.css',
        'css/stm.css',
        'css/front-style.css',
        'css/buttons.css',
        'css/dashicons.css',
        'css/settings.css',
        'css/bootstrap.css',
        'css/style.css',
        'css/style_002.css',
        'css/font-awesome.css',
        'css/main.css',
        'css/select2.css',
        'css/css.css',
        'css/addtoany.css',
        'css/five9-social-widget.css',
        'css/custom.css',
    ];

    public $js = [
        'js/jquery-migrate.js',
        'js/utils.js',
        'js/jquery_003.js',
        'js/jquery_002.js',
        'js/jquery_004.js',
        'js/scripts.js',
        'js/underscore.js',
        'js/shortcode.js',
        'js/backbone.js',
        'js/core.js',
        'js/widget.js',
        'js/mouse.js',
        'js/sortable.js',
        'js/bootstrap.js',
        'js/select2.js',
        'js/masonry.pkgd.min.js',
        'js/jssor.slider-22.0.15.min.js',
        'js/custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
