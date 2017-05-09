<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
use \yii\web\Request;
$baseUrl = str_replace('/backend/web', '/backend/', (new Request)->getBaseUrl());
return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
    ],
    'defaultRoute' => 'site/login',
    'components' => [
        'request' => [
            'baseUrl' => $baseUrl,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'commonfunction' => [

            'class' => 'backend\components\CommonFunctions',

        ],
        'email' => [

            'class' => 'backend\components\Email',

        ],
        'fax' => [

            'class' => 'backend\components\Fax',

        ],
        'Notify' => [
            'class' => 'backend\components\notify\Notification',
        ],
        'userActivity' => [
                'class' => 'backend\components\Activity',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'pdf' => [
            'class' => \kartik\mpdf\Pdf::classname(),
            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
            'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
            // refer settings section for all configuration options
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'baseUrl' => $baseUrl,
            'rules' => [
                'app-user/open-modal/<client_id:\d+>' => 'app-user/open-modal',
                'app-user/add-user/<client_id:\d+>' => 'app-user/add-user',
                'app-user/view/<client_id:\d+>/<id:\d+>' => 'app-user/view',
                'app-user/edit/<client_id:\d+>/<id:\d+>' => 'app-user/edit',
                '<controller>/<action>/<id:\d+>' => '<controller>/<action>',
                'dashboard' => 'dashboard/index',
            ],
        ],

    ],
    'params' => $params,
];
