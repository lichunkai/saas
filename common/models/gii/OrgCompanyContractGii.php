<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "org_company_contract".
 *
 * @property int $id 流水
 * @property int $company_id 机构id
 * @property int $depart_store_id 签约的部门（店）id
 * @property string $contract_version 签约版本：门店版，专业版
 * @property string $contract_money 签约金额
 * @property string $contract_start 合同起始日期
 * @property string $contract_end 合同截至日期
 * @property string $contract_city 签约城市
 * @property int $contract_personal 签约用户数
 * @property int $contract_phone 签约查看电话次数
 * @property int $contract_import 签约导入数据次数
 * @property int $status 合同状态0正常1过期
 * @property int $cid 创建人
 * @property int $uid 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除（0=否  1=是）
 */
class OrgCompanyContractGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'org_company_contract';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'depart_store_id', 'contract_personal', 'contract_phone', 'contract_import', 'status', 'cid', 'uid', 'is_del'], 'integer'],
            [['contract_money'], 'number'],
            [['contract_start', 'contract_end', 'ctime', 'utime'], 'safe'],
            [['contract_version', 'contract_city'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '流水',
            'company_id' => '机构id',
            'depart_store_id' => '签约的部门（店）id',
            'contract_version' => '签约版本：门店版，专业版',
            'contract_money' => '签约金额',
            'contract_start' => '合同起始日期',
            'contract_end' => '合同截至日期',
            'contract_city' => '签约城市',
            'contract_personal' => '签约用户数',
            'contract_phone' => '签约查看电话次数',
            'contract_import' => '签约导入数据次数',
            'status' => '合同状态0正常1过期',
            'cid' => '创建人',
            'uid' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除（0=否  1=是）',
        ];
    }
}
