<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "org_company_order".
 *
 * @property string $order_id 自增流水ID
 * @property string $order_sn 订单号
 * @property string $transaction_id 支付平台批次号
 * @property string $order_title 订单名称
 * @property int $company_id 公司ID
 * @property int $u_id 用户ID
 * @property string $contract_version 产品版本:门店版，专业版
 * @property string $store_id 门店号（升级是为json，其他为单个id）
 * @property int $contract_term 签约年限
 * @property string $order_detail 订单信息记录
 * @property string $amount 支付金额
 * @property int $payment 支付渠道0：未支付；1：支付宝；2：微信；3：银联
 * @property string $real_amount 实际支付金额
 * @property string $paid_time 支付时间
 * @property int $is_invoice 是否申请开票0未申请1申请
 * @property int $status 购买状态0:已下单,1:取消订单,2:支付超时,3:支付成功,4:支付失败,5:申请退款,6:取消退款,7:已退款
 * @property string $ctime 添加时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除
 */
class OrgCompanyOrderGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'org_company_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'u_id', 'contract_term', 'payment', 'is_invoice', 'status', 'is_del'], 'integer'],
            [['order_detail'], 'string'],
            [['amount', 'real_amount'], 'number'],
            [['paid_time', 'ctime', 'utime'], 'safe'],
            [['order_sn'], 'string', 'max' => 32],
            [['transaction_id'], 'string', 'max' => 64],
            [['order_title', 'store_id'], 'string', 'max' => 255],
            [['contract_version'], 'string', 'max' => 10],
            [['order_sn'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '自增流水ID',
            'order_sn' => '订单号',
            'transaction_id' => '支付平台批次号',
            'order_title' => '订单名称',
            'company_id' => '公司ID',
            'u_id' => '用户ID',
            'contract_version' => '产品版本:门店版，专业版',
            'store_id' => '门店号（升级是为json，其他为单个id）',
            'contract_term' => '签约年限',
            'order_detail' => '订单信息记录',
            'amount' => '支付金额',
            'payment' => '支付渠道0：未支付；1：支付宝；2：微信；3：银联',
            'real_amount' => '实际支付金额',
            'paid_time' => '支付时间',
            'is_invoice' => '是否申请开票0未申请1申请',
            'status' => '购买状态0:已下单,1:取消订单,2:支付超时,3:支付成功,4:支付失败,5:申请退款,6:取消退款,7:已退款',
            'ctime' => '添加时间',
            'utime' => '更新时间',
            'is_del' => '是否删除',
        ];
    }
}
