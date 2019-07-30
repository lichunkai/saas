<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_setting_qujian".
 *
 * @property string $qujian_id
 * @property string $qujian_shorthand 区间简写
 * @property string $qujian_name 区间名称
 * @property string $qujian_desp 区间描述
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property string $company_id 公司ID
 */
class ZhSettingQujianGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_setting_qujian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qujian_desp'], 'string'],
            [['ctime', 'utime'], 'safe'],
            [['company_id'], 'integer'],
            [['qujian_shorthand'], 'string', 'max' => 50],
            [['qujian_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'qujian_id' => 'Qujian ID',
            'qujian_shorthand' => '区间简写',
            'qujian_name' => '区间名称',
            'qujian_desp' => '区间描述',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'company_id' => '公司ID',
        ];
    }
}
