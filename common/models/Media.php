<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property integer $id
 * @property string $file_name
 * @property string $file_url
 * @property string $file_path
 * @property string $original_name
 * @property integer $staus
 * @property integer $is_deleted
 * @property integer $created_by
 * @property integer $created_date
 * @property integer $updated_by
 * @property integer $updated_date
 *
 * @property AppUserProfile[] $appUserProfiles
 */
class Media extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_name', 'file_url', 'file_path', 'original_name', 'staus', 'is_deleted', 'created_by', 'created_date', 'updated_by', 'updated_date'], 'required'],
            [['file_url', 'file_path'], 'string'],
            [['staus', 'is_deleted', 'created_by', 'created_date', 'updated_by', 'updated_date'], 'integer'],
            [['file_name', 'original_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_name' => 'File Name',
            'file_url' => 'File Url',
            'file_path' => 'File Path',
            'original_name' => 'Original Name',
            'staus' => 'Staus',
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
    public function getAppUserProfiles()
    {
        return $this->hasMany(AppUserProfile::className(), ['media_id' => 'id']);
    }
}
