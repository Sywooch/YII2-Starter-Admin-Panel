<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dashboard_widgets".
 *
 * @property integer $id
 * @property string $widget-name
 *
 * @property RoleDashboardWidget[] $roleDashboardWidgets
 */
class DashboardWidgets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dashboard_widgets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['widget-name'], 'required'],
            [['widget-name'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'widget-name' => 'Widget Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleDashboardWidgets()
    {
        return $this->hasMany(RoleDashboardWidget::className(), ['dashboard_widget_id' => 'id']);
    }
}
