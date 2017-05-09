<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "role_dashboard_widget".
 *
 * @property integer $id
 * @property integer $dashboard_widget_id
 * @property integer $role_id
 *
 * @property DashboardWidgets $dashboardWidget
 * @property Role $role
 */
class RoleDashboardWidget extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role_dashboard_widget';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dashboard_widget_id', 'role_id'], 'required'],
            [['dashboard_widget_id', 'role_id'], 'integer'],
            [['dashboard_widget_id'], 'exist', 'skipOnError' => true, 'targetClass' => DashboardWidgets::className(), 'targetAttribute' => ['dashboard_widget_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dashboard_widget_id' => 'Dashboard Widget ID',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDashboardWidget()
    {
        return $this->hasOne(DashboardWidgets::className(), ['id' => 'dashboard_widget_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }
}
