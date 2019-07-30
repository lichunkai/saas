<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_order_sell_divide".
 *
 * @property string $divide_id 自增id
 * @property int $order_id 订单id
 * @property int $commission_id 对应的分成用户比例
 * @property int $cost_id 佣金费用记录id
 * @property string $divide_money 费用金额
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 * @property string $company_id 公司ID
 */
class OrderSellDivideGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_order_sell_divide';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'commission_id', 'cost_id', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['divide_money'], 'number'],
            [['ctime', 'utime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'divide_id' => '自增id',
            'order_id' => '订单id',
            'commission_id' => '对应的分成用户比例',
            'cost_id' => '佣金费用记录id',
            'divide_money' => '费用金额',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
            'company_id' => '公司ID',
        ];
    }
}
