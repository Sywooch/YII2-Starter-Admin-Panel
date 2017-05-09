<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "app_user_profile".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property integer $media_id
 * @property string $email
 * @property integer $gender
 * @property string $phone_no
 * @property string $date_of_birth
 * @property integer $is_app_user
 * @property integer $status
 * @property integer $is_deleted
 * @property integer $created_by
 * @property integer $created_date
 * @property integer $updated_by
 * @property integer $updated_date
 *
 * @property Media $media
 * @property User[] $users
 */
class AppUserProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_id', 'gender', 'is_app_user', 'status', 'is_deleted', 'created_by', 'created_date', 'updated_by', 'updated_date'], 'integer'],
            [['email', 'is_app_user', 'status', 'is_deleted', 'created_date'], 'required'],
            [['date_of_birth'], 'safe'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 100],
            [['full_name'], 'string', 'max' => 200],
            [['phone_no'], 'string', 'max' => 20],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'full_name' => 'Full Name',
            'media_id' => 'Media ID',
            'email' => 'Email',
            'gender' => 'Gender',
            'phone_no' => 'Phone No',
            'date_of_birth' => 'Date Of Birth',
            'is_app_user' => 'Is App User',
            'status' => 'Status',
            'is_deleted' => 'Is Deleted',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'updated_by' => 'Updated By',
            'updated_date' => 'Updated Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['profile_id' => 'id']);
    }
}
