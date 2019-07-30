<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_project".
 *
 * @property int $project_id
 * @property string $project_class 类
 * @property string $project_fnc 方法
 * @property int $is_del 是否删除
 */
class ProjectGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_del'], 'integer'],
            [['project_class', 'project_fnc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'Project ID',
            'project_class' => '类',
            'project_fnc' => '方法',
            'is_del' => '是否删除',
        ];
    }
}
