<?php
/**
 * Created by PhpStorm.
 * User: rohan
 * Date: 10/6/16
 * Time: 7:14 PM
 */
namespace frontend\components;

use common\models\Actions;
use common\models\Controllers;
use common\models\Rights;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class CustController extends Controller
{

    public $record;

    public function beforeAction($action)
    {

        if (!parent::beforeAction($action)) {
            return false;
        }
        if (Yii::$app->user->isGuest && !Yii::$app->session->get('access_token')) {
            $this->redirect(\Yii::$app->urlManager->createUrl("site/login"));
            return false; //not run the action
        } else {
            return true; // continue to run action
        }
    }
    public function GUIDv4 ($trim = true)
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true)
                return trim(com_create_guid(), '{}');
            else
                return com_create_guid();
        }

        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        // Fallback (PHP 4.2+)
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);                  // "-"
        $lbrace = $trim ? "" : chr(123);    // "{"
        $rbrace = $trim ? "" : chr(125);    // "}"
        $guidv4 = $lbrace.
            substr($charid,  0,  8).$hyphen.
            substr($charid,  8,  4).$hyphen.
            substr($charid, 12,  4).$hyphen.
            substr($charid, 16,  4).$hyphen.
            substr($charid, 20, 12).
            $rbrace;
        return $guidv4;
    }

}