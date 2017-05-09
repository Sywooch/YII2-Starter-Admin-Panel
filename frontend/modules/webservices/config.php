<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 15/7/16
 * Time: 3:16 PM
 */
return [
    'components' => [
        // list of component configurations''
        'response' => [
            'class' => 'yii\web\Response',
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->isSuccessful == false) {
                    $response->data = [
                        'error' => $response->data,
                    ];
                }
            },
        ],
    ],
    'params' => [
        // list of parameters
    ],
];

