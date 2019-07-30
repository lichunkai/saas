<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_order_sell_commission".
 *
 * @property string $oc_id 自增id
 * @property int $order_id 订单id
 * @property int $depart_id 部门id
 * @property string $depart_name 部门名称
 * @property string $user_job 用户职位
 * @property int $user_id 用户id
 * @property string $user_name 用户名称
 * @property string $reason 参与分成原因
 * @property int $divide_per 分成比例
 * @property int $tc_did 提成人店id
 * @property int $tc_dfzr 店负责人uid
 * @property int $tc_qid 提成区id
 * @property int $tc_qfzr 区负责人uid
 * @property int $tc_dqid 提成大区id
 * @property int $tc_dqfzr 大区负责人
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 * @property string $company_id 公司ID
 */
class OrderSellCommissionGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_order_sell_commission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'depart_id', 'user_id', 'divide_per', 'tc_did', 'tc_dfzr', 'tc_qid', 'tc_qfzr', 'tc_dqid', 'tc_dqfzr', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['depart_name', 'user_job', 'user_name', 'reason'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oc_id' => '自增id',
            'order_id' => '订单id',
            'depart_id' => '部门id',
            'depart_name' => '部门名称',
            'user_job' => '用户职位',
            'user_id' => '用户id',
            'user_name' => '用户名称',
            'reason' => '参与分成原因',
            'divide_per' => '分成比例',
            'tc_did' => '提成人店id',
            'tc_dfzr' => '店负责人uid',
            'tc_qid' => '提成区id',
            'tc_qfzr' => '区负责人uid',
            'tc_dqid' => '提成大区id',
            'tc_dqfzr' => '大区负责人',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
            'company_id' => '公司ID',
        ];
    }
}
