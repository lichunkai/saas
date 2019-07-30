<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_setting_house".
 *
 * @property string $hsetting_id
 * @property string $hsetting_shorthand 英文简写
 * @property string $hsetting_type 类型
 * @property string $hsetting_child 子描述
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property string $company_id 公司ID
 */
class ZhSettingHouseGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_setting_house';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hsetting_child'], 'string'],
            [['ctime', 'utime'], 'safe'],
            [['company_id'], 'integer'],
            [['hsetting_shorthand', 'hsetting_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hsetting_id' => 'Hsetting ID',
            'hsetting_shorthand' => '英文简写',
            'hsetting_type' => '类型',
            'hsetting_child' => '子描述',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'company_id' => '公司ID',
        ];
    }
}
