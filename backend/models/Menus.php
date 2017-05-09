<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menus".
 *
 * @property integer $id
 * @property integer $controller_id
 * @property integer $action_id
 * @property string $icon
 * @property integer $order
 *
 * @property Controllers $controller
 * @property Actions $action
 */
class Menus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controller_id', 'action_id', 'icon', 'order'], 'required'],
            [['controller_id', 'action_id', 'order'], 'integer'],
            [['icon'], 'string', 'max' => 255],
            [['controller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Controllers::className(), 'targetAttribute' => ['controller_id' => 'id']],
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
            'controller_id' => 'Controller ID',
            'action_id' => 'Action ID',
            'icon' => 'Icon',
            'order' => 'Order',
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
    public function getAction()
    {
        return $this->hasOne(Actions::className(), ['id' => 'action_id']);
    }
}
