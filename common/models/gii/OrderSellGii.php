<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_order_sell".
 *
 * @property string $order_id 自增id
 * @property int $order_type 订单类型1租赁2普通3高端
 * @property string $order_sn 订单号
 * @property string $agreement_sn 协议编号
 * @property string $contract_sn 合同编号
 * @property string $contract_name 合同名称
 * @property string $order_status 订单状态
 * @property string $order_status_remark 状态变更备注
 * @property int $order_deal_type 交易类型：1本公司自有2合作房源3合作客源4代办过户
 * @property string $order_price 成交价
 * @property int $order_loan 是否有贷款
 * @property string $order_owner_commission 业主应收佣金
 * @property string $order_zaxiang_commission 杂项收入
 * @property string order_zaxiang_type 杂项收入类型 1评估 0垫资
 * @property string $order_customer_commission 客户应收佣金
 * @property int $order_auto_divide 是否自动分成
 * @property string $order_divide_status 分成状态
 * @property string $order_deal_date 成交日期
 * @property string $order_contract_date 合同结案时间
 * @property string $order_commission_date 佣金结案时间
 * @property int $order_deal_did 成交人的部门
 * @property int $order_deal_user 成交人
 * @property string $order_deal_username 成交人姓名
 * @property int $order_property_did 权证人部门
 * @property int $order_property_user 权证人
 * @property string $order_property_username 权证人名
 * @property string $order_start_time 出租开始时间
 * @property string $order_end_time 出租结束时间
 * @property string $order_addition_terms 补充条款
 * @property string $house_name 项目名称
 * @property string $house_land_certificate 土地证号
 * @property string $house_property_certificate 房屋产权证号
 * @property string $house_type 房屋类型
 * @property int $dts_id 片区区ID
 * @property string $dts_name 片区名称
 * @property int $village_id 小区ID
 * @property string $village_name 小区名称
 * @property string $house_building 楼栋
 * @property string $house_unit 房屋单元
 * @property string $house_door 门牌号
 * @property double $house_area 房屋面积
 * @property string $owner_sn 甲方编号
 * @property string $owner_name 业主姓名
 * @property string $owner_phone 业主电话
 * @property string $owner_sex 业主性别
 * @property string $owner_idno 业主证件号
 * @property string $owner_address 业主地址
 * @property string $customer_sn 客户编号
 * @property string $customer_name 客户姓名
 * @property string $customer_phone 客户电话
 * @property string $customer_sex 客户性别
 * @property string $customer_idno 客户证件号
 * @property string $customer_address 客户地址
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $auth_uid 用户ID
 * @property int $auth_rid 组ID
 * @property int $auth_ruid 组负责人ID
 * @property int $auth_sid 店ID
 * @property int $auth_suid 店负责人ID
 * @property int $auth_aid 区ID
 * @property int $auth_auid 区负责人ID
 * @property int $auth_baid 大区iD
 * @property int $auth_bauid 大区负责人iD
 * @property int $auth_cid 公司ID
 * @property int $is_del
 * @property int $order_linkage_did 联动划成人的部门
 * @property int $order_linkage_user 联动划成人
 * @property string $order_linkage_username 联动划成人姓名
 * @property int $order_linkage_per 联成划成比例
 * @property int $order_consult_did 协商划成人的部门
 * @property int $order_consult_user 协商划成人
 * @property string $order_consult_username 协商划成人姓名
 * @property int $order_consult_per 协商划成人比例
 * @property int $order_transfer_id 过户流程id
 * @property string $order_transfer_process 过户流程名称
 * @property string $contract_image 签约值图片
 * @property string $company_id 公司ID
 */
class OrderSellGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_order_sell';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_type', 'order_deal_type', 'order_loan', 'order_auto_divide', 'order_deal_did', 'order_deal_user', 'order_property_did', 'order_property_user', 'dts_id', 'village_id', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_ruid', 'auth_sid', 'auth_suid', 'auth_aid', 'auth_auid', 'auth_baid', 'auth_bauid', 'auth_cid', 'is_del', 'order_linkage_did', 'order_linkage_user', 'order_linkage_per', 'order_consult_did', 'order_consult_user', 'order_consult_per', 'order_transfer_id', 'company_id'], 'integer'],
            [['order_price', 'order_owner_commission','order_zaxiang_commission', 'order_customer_commission', 'house_area'], 'number'],
            [['order_deal_date', 'order_contract_date', 'order_commission_date', 'order_start_time', 'order_end_time', 'ctime', 'utime'], 'safe'],
            [['order_addition_terms', 'contract_image'], 'string'],
            [['order_sn', 'agreement_sn', 'order_deal_username', 'order_property_username', 'owner_name', 'owner_phone', 'order_linkage_username', 'order_consult_username'], 'string', 'max' => 30],
            [['contract_sn', 'contract_name', 'house_name', 'house_land_certificate', 'house_property_certificate', 'owner_sn', 'owner_idno', 'customer_sn', 'customer_idno', 'order_transfer_process'], 'string', 'max' => 50],
            [['order_status', 'order_divide_status', 'order_zaxiang_type','house_type', 'house_building', 'house_unit', 'house_door'], 'string', 'max' => 20],
            [['order_status_remark'], 'string', 'max' => 200],
            [['dts_name', 'village_name', 'owner_address', 'customer_name', 'customer_phone', 'customer_address'], 'string', 'max' => 255],
            [['owner_sex', 'customer_sex'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '自增id',
            'order_type' => '订单类型1租赁2普通3高端',
            'order_sn' => '订单号',
            'agreement_sn' => '协议编号',
            'contract_sn' => '合同编号',
            'contract_name' => '合同名称',
            'order_status' => '订单状态',
            'order_status_remark' => '状态变更备注',
            'order_deal_type' => '交易类型：1本公司自有2合作房源3合作客源4代办过户',
            'order_price' => '成交价',
            'order_loan' => '是否有贷款',
            'order_owner_commission' => '业主应收佣金',
            'order_customer_commission' => '客户应收佣金',
            'order_zaxiang_commission' => '杂项收入',
            'order_zaxiang_type'   => '杂项收入类型 1 评估费 0 垫资费',
            'order_auto_divide' => '是否自动分成',
            'order_divide_status' => '分成状态',
            'order_deal_date' => '成交日期',
            'order_contract_date' => '合同结案时间',
            'order_commission_date' => '佣金结案时间',
            'order_deal_did' => '成交人的部门',
            'order_deal_user' => '成交人',
            'order_deal_username' => '成交人姓名',
            'order_property_did' => '权证人部门',
            'order_property_user' => '权证人',
            'order_property_username' => '权证人名',
            'order_start_time' => '出租开始时间',
            'order_end_time' => '出租结束时间',
            'order_addition_terms' => '补充条款',
            'house_name' => '项目名称',
            'house_land_certificate' => '土地证号',
            'house_property_certificate' => '房屋产权证号',
            'house_type' => '房屋类型',
            'dts_id' => '片区区ID',
            'dts_name' => '片区名称',
            'village_id' => '小区ID',
            'village_name' => '小区名称',
            'house_building' => '楼栋',
            'house_unit' => '房屋单元',
            'house_door' => '门牌号',
            'house_area' => '房屋面积',
            'owner_sn' => '甲方编号',
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
            'auth_ruid' => '组负责人ID',
            'auth_sid' => '店ID',
            'auth_suid' => '店负责人ID',
            'auth_aid' => '区ID',
            'auth_auid' => '区负责人ID',
            'auth_baid' => '大区iD',
            'auth_bauid' => '大区负责人iD',
            'auth_cid' => '公司ID',
            'is_del' => 'Is Del',
            'order_linkage_did' => '联动划成人的部门',
            'order_linkage_user' => '联动划成人',
            'order_linkage_username' => '联动划成人姓名',
            'order_linkage_per' => '联成划成比例',
            'order_consult_did' => '协商划成人的部门',
            'order_consult_user' => '协商划成人',
            'order_consult_username' => '协商划成人姓名',
            'order_consult_per' => '协商划成人比例',
            'order_transfer_id' => '过户流程id',
            'order_transfer_process' => '过户流程名称',
            'contract_image' => '签约值图片',
            'company_id' => '公司ID',
        ];
    }
}
