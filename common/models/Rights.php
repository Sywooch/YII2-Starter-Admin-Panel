<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rights".
 *
 * @property integer $id
 * @property integer $role_id
 * @property integer $controller_id
 * @property integer $action_id
 *
 * @property Controllers $controller
 * @property Role $role
 * @property Actions $action
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
            [['controller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Controllers::className(), 'targetAttribute' => ['controller_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => Actions::className(), 'targetAttribute' => ['action_id' => 'id']],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getController()
    {
        return $this->hasOne(Controllers::className(), ['id' => 'controller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(Actions::className(), ['id' => 'action_id']);
    }
}
