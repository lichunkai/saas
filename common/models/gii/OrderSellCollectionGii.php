<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_order_sell_collection".
 *
 * @property string $collection_id 自增id
 * @property int $order_id 订单id
 * @property int $house_id 房源id
 * @property int $xiading_id 下定id
 * @property int $xiading_status 意向金管理将要变更的状态
 * @property string $xiading_contractno 协议书
 * @property string $collection_no 票据号
 * @property int $collection_type 费用类型1代收类型2代付类型3意向金
 * @property string $collection_purpose 费用用途
 * @property string $collection_way 支付方式
 * @property string $collection_money 代收代付金额
 * @property int $collection_payer 付费人/收取人 1业主2客户
 * @property string $collection_day 收款日期
 * @property string $collection_image 费用凭证图片
 * @property string $collection_remark 收费备注
 * @property string $collection_reason 反驳原因
 * @property int $collection_status 记录状态0未确认1确认2驳回
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 * @property string $company_id 公司ID
 */
class OrderSellCollectionGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_order_sell_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'house_id', 'xiading_id', 'xiading_status', 'collection_type', 'collection_payer', 'collection_status', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['collection_money'], 'number'],
            [['collection_day', 'ctime', 'utime'], 'safe'],
            [['collection_image'], 'string'],
            [['xiading_contractno'], 'string', 'max' => 100],
            [['collection_no'], 'string', 'max' => 50],
            [['collection_purpose'], 'string', 'max' => 30],
            [['collection_way'], 'string', 'max' => 20],
            [['collection_remark', 'collection_reason'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'collection_id' => '自增id',
            'order_id' => '订单id',
            'house_id' => '房源id',
            'xiading_id' => '下定id',
            'xiading_status' => '意向金管理将要变更的状态',
            'xiading_contractno' => '协议书',
            'collection_no' => '票据号',
            'collection_type' => '费用类型1代收类型2代付类型3意向金',
            'collection_purpose' => '费用用途',
            'collection_way' => '支付方式',
            'collection_money' => '代收代付金额',
            'collection_payer' => '付费人/收取人 1业主2客户',
            'collection_day' => '收款日期',
            'collection_image' => '费用凭证图片',
            'collection_remark' => '收费备注',
            'collection_reason' => '反驳原因',
            'collection_status' => '记录状态0未确认1确认2驳回',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
            'company_id' => '公司ID',
        ];
    }
}
