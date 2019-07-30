<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_order_rent".
 *
 * @property string $order_id 自增id
 * @property string $order_sn 订单号
 * @property string $agreement_sn 协议编号
 * @property int $order_status 订单状态
 * @property string $order_price 成交价
 * @property string $order_commission 应收佣金
 * @property int $order_divide_status 分成状态
 * @property string $order_deal_date 成交日期
 * @property int $order_deal_user 成交人
 * @property string $order_deal_username 成交人姓名
 * @property int $order_property_user 权证人
 * @property string $order_property_username 权证人名
 * @property string $house_sn 房屋编号
 * @property string $house_name 项目名称
 * @property string $house_land_certificate 土地证号
 * @property string $house_property_certificate 房屋产权证号
 * @property int $house_type 房屋类型
 * @property string $house_district 项目区域
 * @property string $house_building 楼栋
 * @property string $house_unit 房屋单元
 * @property string $house_door 门牌号
 * @property double $house_area 房屋面积
 * @property string $owner_name 业主姓名
 * @property string $owner_phone 业主电话
 * @property int $owner_sex 业主性别
 * @property string $owner_idno 业主证件号
 * @property string $owner_address 业主地址
 * @property string $customer_sn 客户编号
 * @property string $customer_name 客户姓名
 * @property string $customer_phone 客户电话
 * @property int $customer_sex 客户性别
 * @property string $customer_idno 客户证件号
 * @property string $customer_address 客户地址
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $auth_uid 用户ID
 * @property int $auth_rid 组ID
 * @property int $auth_sid 店ID
 * @property int $auth_aid 区ID
 * @property int $auth_baid 大区iD
 * @property int $auth_cid 公司ID
 * @property int $is_del
 */
class OrderRentGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_order_rent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_status', 'order_divide_status', 'order_deal_user', 'order_property_user', 'house_type', 'owner_sex', 'customer_sex', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del'], 'integer'],
            [['order_price', 'order_commission', 'house_area'], 'number'],
            [['order_deal_date', 'ctime', 'utime'], 'safe'],
            [['order_sn', 'agreement_sn', 'order_deal_username', 'order_property_username', 'owner_name', 'owner_phone'], 'string', 'max' => 30],
            [['house_sn', 'house_building', 'house_unit', 'house_door'], 'string', 'max' => 20],
            [['house_name', 'house_land_certificate', 'house_property_certificate', 'house_district', 'owner_idno', 'customer_sn', 'customer_idno'], 'string', 'max' => 50],
            [['owner_address', 'customer_name', 'customer_phone', 'customer_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '自增id',
            'order_sn' => '订单号',
            'agreement_sn' => '协议编号',
            'order_status' => '订单状态',
            'order_price' => '成交价',
            'order_commission' => '应收佣金',
            'order_divide_status' => '分成状态',
            'order_deal_date' => '成交日期',
            'order_deal_user' => '成交人',
            'order_deal_username' => '成交人姓名',
            'order_property_user' => '权证人',
            'order_property_username' => '权证人名',
            'house_sn' => '房屋编号',
            'house_name' => '项目名称',
            'house_land_certificate' => '土地证号',
            'house_property_certificate' => '房屋产权证号',
            'house_type' => '房屋类型',
            'house_district' => '项目区域',
            'house_building' => '楼栋',
            'house_unit' => '房屋单元',
            'house_door' => '门牌号',
            'house_area' => '房屋面积',
            'owner_name' => '业主姓名',
            'owner_phone' => '业主电话',
            'owner_sex' => '业主性别',
            'owner_idno' => '业主证件号',
            'owner_address' => '业主地址',
            'customer_sn' => '客户编号',
            'customer_name' => '客户姓名',
            'customer_phone' => '客户电话',
            'customer_sex' => '客户性别',
            'customer_idno' => '客户证件号',
            'customer_address' => '客户地址',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'auth_uid' => '用户ID',
            'auth_rid' => '组ID',
            'auth_sid' => '店ID',
            'auth_aid' => '区ID',
            'auth_baid' => '大区iD',
            'auth_cid' => '公司ID',
            'is_del' => 'Is Del',
        ];
    }
}
