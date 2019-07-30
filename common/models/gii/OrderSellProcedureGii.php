<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_order_sell_procedure".
 *
 * @property string $procedure_id 自增id
 * @property int $order_id 订单id
 * @property string $procedure_name 流程名称
 * @property string $procedure_expect_day 流程预计时间
 * @property string $procedure_finish_day 完成时间
 * @property int $procedure_status 记录状态0未完成1已完成
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 * @property string $company_id 公司ID
 */
class OrderSellProcedureGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_order_sell_procedure';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'procedure_status', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['procedure_expect_day', 'procedure_finish_day', 'ctime', 'utime'], 'safe'],
            [['procedure_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'procedure_id' => '自增id',
            'order_id' => '订单id',
            'procedure_name' => '流程名称',
            'procedure_expect_day' => '流程预计时间',
            'procedure_finish_day' => '完成时间',
            'procedure_status' => '记录状态0未完成1已完成',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
            'company_id' => '公司ID',
        ];
    }
}
