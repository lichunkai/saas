<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_order_sell_img".
 *
 * @property string $oi_id 字段ID
 * @property int $order_id 订单id
 * @property string $oi_url 图片地址
 * @property int $oi_type 订单图片材料分配
 * @property int $oi_status 是否审核
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除 0=没有删除  1=删除
 */
class OrderSellmgGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_order_sell_img';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'oi_type', 'oi_status', 'c_id', 'u_id', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['oi_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oi_id' => '字段ID',
            'order_id' => '订单id',
            'oi_url' => '图片地址',
            'oi_type' => '订单图片材料分配',
            'oi_status' => '是否审核',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除 0=没有删除  1=删除',
        ];
    }
}
