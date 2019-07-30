<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_setting_juece".
 *
 * @property string $jsetting_id
 * @property string $jsetting_shorthand 英文简写
 * @property string $jsetting_type 类型
 * @property string $jsetting_name 名称
 * @property string $jsetting_desp 说明
 * @property string $val_type 变量类型
 * @property string $val 变量值
 * @property string $ctime 修改时间
 * @property string $utime 修改时间
 * @property string $company_id 公司ID
 */
class ZhSettingJueceGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_setting_juece';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctime', 'utime'], 'safe'],
            [['company_id'], 'integer'],
            [['jsetting_shorthand', 'jsetting_type'], 'string', 'max' => 50],
            [['jsetting_name', 'jsetting_desp', 'val_type', 'val'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jsetting_id' => 'Jsetting ID',
            'jsetting_shorthand' => '英文简写',
            'jsetting_type' => '类型',
            'jsetting_name' => '名称',
            'jsetting_desp' => '说明',
            'val_type' => '变量类型',
            'val' => '变量值',
            'ctime' => '修改时间',
            'utime' => '修改时间',
            'company_id' => '公司ID',
        ];
    }
}
