<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_setting_finance".
 *
 * @property string $finance_id 普通配置ID
 * @property string $finance_shorthand 普通配置简写，英文
 * @property string $finance_name 普通配置名
 * @property string $finance_desp 普通配置描述JSON格式
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property string $company_id 公司ID
 */
class ZhSettingFinanceGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_setting_finance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_desp'], 'string'],
            [['ctime', 'utime'], 'safe'],
            [['company_id'], 'integer'],
            [['finance_shorthand', 'finance_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'finance_id' => '普通配置ID',
            'finance_shorthand' => '普通配置简写，英文',
            'finance_name' => '普通配置名',
            'finance_desp' => '普通配置描述JSON格式',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'company_id' => '公司ID',
        ];
    }
}
