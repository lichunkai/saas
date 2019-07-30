<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_setting_base".
 *
 * @property string $base_id 普通配置ID
 * @property string $base_shorthand 普通配置简写，英文
 * @property string $base_name 普通配置名
 * @property string $base_desp 普通配置描述JSON格式
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property string $company_id 公司ID
 */
class ZhSettingBaseGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_setting_base';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['base_desp'], 'string'],
            [['ctime', 'utime'], 'safe'],
            [['company_id'], 'integer'],
            [['base_shorthand', 'base_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'base_id' => '普通配置ID',
            'base_shorthand' => '普通配置简写，英文',
            'base_name' => '普通配置名',
            'base_desp' => '普通配置描述JSON格式',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'company_id' => '公司ID',
        ];
    }
}
