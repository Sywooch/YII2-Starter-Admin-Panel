<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "devices".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $device_platform
 * @property string $device_token
 * @property string $device_unique_id
 * @property integer $is_login
 * @property string $access_token
 * @property integer $login_time
 * @property string $os
 * @property string $device_model
 * @property integer $created_date
 *
 * @property User $user
 */
class Devices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'device_platform', 'device_token', 'device_unique_id', 'is_login', 'login_time', 'os', 'device_model', 'created_date'], 'required'],
            [['user_id', 'is_login', 'login_time', 'created_date'], 'integer'],
            [['device_platform'], 'string'],
            [['device_token', 'device_unique_id', 'os', 'device_model'], 'string', 'max' => 100],
            [['access_token'], 'string', 'max' => 32],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'device_platform' => 'Device Platform',
            'device_token' => 'Device Token',
            'device_unique_id' => 'Device Unique ID',
            'is_login' => 'Is Login',
            'access_token' => 'Access Token',
            'login_time' => 'Login Time',
            'os' => 'Os',
            'device_model' => 'Device Model',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
