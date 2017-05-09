<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "social".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $social_media_platform
 * @property string $access_token
 * @property integer $token_expiry
 * @property string $socialmedia_id
 * @property integer $is_connected
 * @property string $token_updated
 *
 * @property User $user
 */
class Social extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'social_media_platform', 'socialmedia_id', 'is_connected'], 'required'],
            [['user_id', 'token_expiry', 'is_connected'], 'integer'],
            [['social_media_platform', 'access_token'], 'string'],
            [['token_updated'], 'safe'],
            [['socialmedia_id'], 'string', 'max' => 100],
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
            'social_media_platform' => 'Social Media Platform',
            'access_token' => 'Access Token',
            'token_expiry' => 'Token Expiry',
            'socialmedia_id' => 'Socialmedia ID',
            'is_connected' => 'Is Connected',
            'token_updated' => 'Token Updated',
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
