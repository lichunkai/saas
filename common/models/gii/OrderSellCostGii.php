<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_order_sell_cost".
 *
 * @property string $cost_id 自增id
 * @property int $order_id 订单id
 * @property int $cost_type 费用类型1佣金类型2杂项类型3折佣类型
 * @property int $cost_purpose 费用用途1收入2支出
 * @property string $cost_project 费用项目
 * @property int $cost_payer 缴费人1业主2客户3财务
 * @property string $cost_way 支付方式
 * @property string $cost_money 收取金额
 * @property string $cost_day 收款日期
 * @property string $cost_image 费用凭据图片
 * @property string $cost_remark 收费备注
 * @property string $cost_reason 反驳原因
 * @property int $cost_status 记录状态0未确认1确认2驳回
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 * @property string $company_id 公司ID
 */
class OrderSellCostGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_order_sell_cost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'cost_type', 'cost_purpose', 'cost_payer', 'cost_status', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['cost_money'], 'number'],
            [['cost_day', 'ctime', 'utime'], 'safe'],
            [['cost_image'], 'string'],
            [['cost_project'], 'string', 'max' => 50],
            [['cost_way'], 'string', 'max' => 20],
            [['cost_remark', 'cost_reason'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cost_id' => '自增id',
            'order_id' => '订单id',
            'cost_type' => '费用类型1佣金类型2杂项类型3折佣类型',
            'cost_purpose' => '费用用途1收入2支出',
            'cost_project' => '费用项目',
            'cost_payer' => '缴费人1业主2客户3财务',
            'cost_way' => '支付方式',
            'cost_money' => '收取金额',
            'cost_day' => '收款日期',
            'cost_image' => '费用凭据图片',
            'cost_remark' => '收费备注',
            'cost_reason' => '反驳原因',
            'cost_status' => '记录状态0未确认1确认2驳回',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
            'company_id' => '公司ID',
        ];
    }
}
