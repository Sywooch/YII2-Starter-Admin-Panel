<?php

/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 23/1/17
 * Time: 7:56 PM
 */
namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;

class SyncWidget extends Widget
{
    public function run()
    {
        return $this->render('sync');
    }
}