<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_order_rent_tel".
 *
 * @property int $tel_id 字段ID
 * @property int $order_id
 * @property int $type 电话所属1甲方2乙方
 * @property string $phone 电话
 * @property string $relation 双方关系
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $auth_uid 用户ID
 * @property int $auth_rid 组ID
 * @property int $auth_sid 店ID
 * @property int $auth_aid 区ID
 * @property int $auth_baid 大区ID
 * @property int $auth_cid 公司ID
 * @property int $is_del 是否删除 0=没有删除  1=删除
 */
class OrderRentTelGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_order_rent_tel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'type', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['phone', 'relation'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tel_id' => '字段ID',
            'order_id' => 'Order ID',
            'type' => '电话所属1甲方2乙方',
            'phone' => '电话',
            'relation' => '双方关系',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'auth_uid' => '用户ID',
            'auth_rid' => '组ID',
            'auth_sid' => '店ID',
            'auth_aid' => '区ID',
            'auth_baid' => '大区ID',
            'auth_cid' => '公司ID',
            'is_del' => '是否删除 0=没有删除  1=删除',
        ];
    }
}
