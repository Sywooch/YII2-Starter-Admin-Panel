<?php

namespace frontend\modules\expedia;

/**
 * expedia module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\expedia\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        // custom initialization code goes here
        \Yii::$app->set('response', [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->isSuccessful == false) {
                    $response->data = [
                        'error' => $response->data,
                    ];
                }
            },
            'format' => 'json'
        ]);

    }
}
