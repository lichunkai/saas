<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_setting_required".
 *
 * @property string $rsetting_id 必填项ID
 * @property string $rsetting_type 必填项类型
 * @property string $rsetting_options 所有选项
 * @property string $rsetting_desp 必填字段集
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property string $company_id 公司ID
 */
class ZhSettingRequiredGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_setting_required';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rsetting_options', 'rsetting_desp'], 'string'],
            [['ctime', 'utime'], 'safe'],
            [['company_id'], 'integer'],
            [['rsetting_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rsetting_id' => '必填项ID',
            'rsetting_type' => '必填项类型',
            'rsetting_options' => '所有选项',
            'rsetting_desp' => '必填字段集',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'company_id' => '公司ID',
        ];
    }
}
