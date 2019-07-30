<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "org_company_consign".
 *
 * @property string $c_id 委托ID
 * @property int $c_type 委托类型1卖2买3出租4租房
 * @property int $company_id 公司id
 * @property int $u_id 用户id
 * @property string $user_name 客户名称
 * @property string $user_phone 客户手机
 * @property int $user_sex 客户姓名1男2女
 * @property string $community_name 小区名称
 * @property string $buliding_no 楼栋号
 * @property string $door_no 单元号
 * @property string $room_no 房号
 * @property int $house_type_shi 几室
 * @property int $house_type_ting 几厅
 * @property int $house_type_wei 几卫
 * @property double $house_area 房屋面积
 * @property string $sell_price 房屋期望售价
 * @property string $buy_district 购买房子区域
 * @property string $buy_trade_area 商圈
 * @property int $buy_area_from 买房期望面积下限
 * @property int $buy_area_to 买房期望面积上限
 * @property string $buy_price_from 买房预算下限
 * @property string $buy_price_to 买房预算上限
 * @property int $lease_area_from 租房期望面积
 * @property int $lease_area_to 租房期望面积
 * @property string $lease_price_from 租房预算下限
 * @property string $lease_price_to 租房预算上限
 * @property int $lease_type 租房方式1整租2合租
 * @property double $hire_area 出租面积
 * @property int $hire_type 出租房方式1整租2合租
 * @property string $hire_price 房屋期望租价
 * @property string $hire_matching 租房配套家具家电
 * @property string $ctime 新建时间
 * @property string $utime 修改时间
 * @property int $is_del 0表示不删除，1表示删除
 */
class OrgCompanyConsignGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'org_company_consign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_type', 'company_id', 'u_id', 'user_sex', 'house_type_shi', 'house_type_ting', 'house_type_wei', 'buy_area_from', 'buy_area_to', 'lease_area_from', 'lease_area_to', 'lease_type', 'hire_type', 'is_del'], 'integer'],
            [['house_area', 'sell_price', 'buy_price_from', 'buy_price_to', 'lease_price_from', 'lease_price_to', 'hire_area', 'hire_price'], 'number'],
            [['ctime', 'utime'], 'safe'],
            [['user_name', 'user_phone'], 'string', 'max' => 100],
            [['community_name'], 'string', 'max' => 150],
            [['buliding_no', 'door_no', 'room_no', 'buy_district', 'buy_trade_area'], 'string', 'max' => 30],
            [['hire_matching'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'c_id' => '委托ID',
            'c_type' => '委托类型1卖2买3出租4租房',
            'company_id' => '公司id',
            'u_id' => '用户id',
            'user_name' => '客户名称',
            'user_phone' => '客户手机',
            'user_sex' => '客户姓名1男2女',
            'community_name' => '小区名称',
            'buliding_no' => '楼栋号',
            'door_no' => '单元号',
            'room_no' => '房号',
            'house_type_shi' => '几室',
            'house_type_ting' => '几厅',
            'house_type_wei' => '几卫',
            'house_area' => '房屋面积',
            'sell_price' => '房屋期望售价',
            'buy_district' => '购买房子区域',
            'buy_trade_area' => '商圈',
            'buy_area_from' => '买房期望面积下限',
            'buy_area_to' => '买房期望面积上限',
            'buy_price_from' => '买房预算下限',
            'buy_price_to' => '买房预算上限',
            'lease_area_from' => '租房期望面积',
            'lease_area_to' => '租房期望面积',
            'lease_price_from' => '租房预算下限',
            'lease_price_to' => '租房预算上限',
            'lease_type' => '租房方式1整租2合租',
            'hire_area' => '出租面积',
            'hire_type' => '出租房方式1整租2合租',
            'hire_price' => '房屋期望租价',
            'hire_matching' => '租房配套家具家电',
            'ctime' => '新建时间',
            'utime' => '修改时间',
            'is_del' => '0表示不删除，1表示删除',
        ];
    }
}
