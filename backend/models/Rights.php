<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rights".
 *
 * @property integer $id
 * @property integer $role_id
 * @property integer $controller_id
 * @property integer $action_id
 */
class Rights extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rights';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'controller_id', 'action_id'], 'required'],
            [['role_id', 'controller_id', 'action_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'controller_id' => 'Controller ID',
            'action_id' => 'Action ID',
        ];
    }
}
