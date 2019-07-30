<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_setting_transfer".
 *
 * @property string $transfer_id 普通配置ID
 * @property string $transfer_name 过户流程名称
 * @property string $transfer_owner_materials 业主材料明细(JSON格式)
 * @property string $transfer_customer_materials 客户材料明细(JSON格式)
 * @property string $transfer_process 过户流程配置(JSON格式)
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property string $company_id 公司ID
 */
class ZhSettingTransferGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_setting_transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transfer_owner_materials', 'transfer_customer_materials', 'transfer_process'], 'string'],
            [['ctime', 'utime'], 'safe'],
            [['company_id'], 'integer'],
            [['transfer_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transfer_id' => '普通配置ID',
            'transfer_name' => '过户流程名称',
            'transfer_owner_materials' => '业主材料明细(JSON格式)',
            'transfer_customer_materials' => '客户材料明细(JSON格式)',
            'transfer_process' => '过户流程配置(JSON格式)',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'company_id' => '公司ID',
        ];
    }
}
