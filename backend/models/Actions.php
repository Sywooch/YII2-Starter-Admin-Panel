<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "actions".
 *
 * @property integer $id
 * @property integer $controller_id
 * @property string $action_name
 * @property string $slug
 *
 * @property Controllers $controller
 */
class Actions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'actions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controller_id', 'action_name', 'slug'], 'required'],
            [['controller_id'], 'integer'],
            [['controller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Controllers::className(), 'targetAttribute' => ['controller_id' => 'id']],
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
            'action_name' => 'Action Name',
            'slug' => 'Slug',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getController()
    {
        return $this->hasOne(Controllers::className(), ['id' => 'controller_id']);
    }
}
