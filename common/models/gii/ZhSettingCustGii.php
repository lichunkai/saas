<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_setting_cust".
 *
 * @property string $csetting_id
 * @property string $csetting_shorthand 英文简写
 * @property string $csetting_type 类型
 * @property string $csetting_child 子描述
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property string $company_id 公司ID
 */
class ZhSettingCustGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_setting_cust';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['csetting_child'], 'string'],
            [['ctime', 'utime'], 'safe'],
            [['company_id'], 'integer'],
            [['csetting_shorthand', 'csetting_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'csetting_id' => 'Csetting ID',
            'csetting_shorthand' => '英文简写',
            'csetting_type' => '类型',
            'csetting_child' => '子描述',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'company_id' => '公司ID',
        ];
    }
}
