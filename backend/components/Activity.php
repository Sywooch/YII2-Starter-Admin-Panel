<?php
/**
 * Created by PhpStorm.
 * User: disha
 * Date: 16/11/16
 * Time: 4:30 PM
 */

namespace backend\components;

use Yii;

use yii\base\Component;
use common\models\UserActivity;

class Activity extends Component
{
    public function add($recordID, $actionID)
    {

        $activity = new UserActivity();
        $activity->user_id = Yii::$app->user->id;
        $activity->record_id = $recordID;
        $activity->action_id = $actionID;
        $activity->created_at = time();
        if ($activity->save()) {
            return true;
        } else {
            return false;
        }
    }
}