<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'vendors/font-awesome/css/font-awesome.min.css',
        'css/animate.min.css',
        'css/temp.css',
        'css/bootstrap-wysihtml5.css',
        'css/flatelements.css',
        'css/jquery.atwho.css',
        'theme-assets/css/theme.css',
        'plugins/timepicker/jquery.timepicker.min.css',
        'plugins/fancybox/dist/jquery.fancybox.min.css',
        'plugins/dropzone/dropzone.css'
    ];
    public $js = [
        'js/ekko-lightbox-modified.js?v=1482936345',
        'js/modernizr.js?v=1482936345',
        'js/holder.js',
        'plugins/timepicker/jquery.timepicker.min.js',
        'js/jquery.cookie.js?v=1482936345',
        'js/jquery.highlight.min.js?v=1482936345',
        'js/jquery.autosize.min.js?v=1482936345',
        'js/wysihtml5-0.3.0.js?v=1482936345',
        'js/bootstrap3-wysihtml5.js?v=1482936345',
        'js/jquery.color-2.1.0.min.js?v=1482936345',
        'js/jquery.flatelements.js?v=1482936345',
        'js/jquery.loader.js?v=1482936345',
        'js/desktop-notify-min.js?v=1482936345',
        'js/desktop-notify-config.js?v=1482936345',
        'js/jquery.nicescroll.min.js?v=1482936345',
        'plugins/fancybox/dist/jquery.fancybox.min.js',
        'plugins/dropzone/dropzone.js',
        'plugins/bootbox/bootbox.min.js',
        'js/custom.js'

    ];


    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
