<?php
namespace frontend\modules\webservices;

/**
 * expedia module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\webservices\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
        //\Yii::configure($this, require(__DIR__ . '/config.php'));
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
