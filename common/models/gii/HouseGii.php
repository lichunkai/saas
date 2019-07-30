<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house".
 *
 * @property int $house_id
 * @property string $house_uuid 房源uuid
 * @property int $sale_type 交易类型 1=二手出租 2=出售 3=高端出售
 * @property string $house_sn 房源编号
 * @property string $house_level 房源等级
 * @property string $change_public_time 提供时间
 * @property int $house_status 房源状态1=有效 2=成交 3=无效
 * @property int $house_private 公盘私盘 1=共享盘 2=店公盘 3=公司公盘 4=公盘
 * @property string $house_school 房源学区
 * @property int $dts_id 片区区ID
 * @property string $dts_name 片区名称
 * @property int $village_id 小区ID
 * @property string $village_name 小区名称
 * @property string $loudong_name 楼栋
 * @property string $danyuan_name 单元
 * @property string $fanghao_name 房号
 * @property string $customer_name 客户姓名
 * @property string $customer_sex 客户性别
 * @property string $customer_phone 客户电话
 * @property string $customer_type 客户类型
 * @property string $house_title 房源标题
 * @property string $house_tag 房源标签
 * @property string $house_tuijian_tag 房源推荐标签
 * @property string $sell_price 售价
 * @property int $huxing_shi 户型-室
 * @property int $huxing_ting 户型-厅
 * @property int $huxing_wei 户型-卫
 * @property int $huxing_chu 户型-厨
 * @property int $huxing_yangtai 户型-阳台
 * @property double $jianzhumianji 建筑面积
 * @property double $shiyongmianji 实用面积
 * @property int $louceng_now 楼层-当前
 * @property int $louceng_total 楼层-总共
 * @property string $chaoxiang 朝向
 * @property int $tihu_ti 梯户-梯
 * @property int $tihu_hu 梯户-户
 * @property string $zhuangxiu 装修
 * @property string $peitao 配套
 * @property string $xianzhuang 现状
 * @property string $fangwuleixing 房屋类型
 * @property string $jianzhujiegou 建筑结构
 * @property string $jianzaoniandai 建造年代
 * @property string $chanquanxingzhi 产权性质
 * @property string $chanquannianxian 产权年限
 * @property string $chanzhengriqi 产证日期
 * @property string $fangyuanshuifei 房源税费
 * @property string $kanfangfangshi 看房方式
 * @property string $laiyuan 来源
 * @property string $weituobianhao 委托编号
 * @property string $fukuanfangshi 付款方式
 * @property string $yaoshi_dian 钥匙店
 * @property int $is_yaoshi 是否有钥匙 0=没有  1=有
 * @property int $is_yaoshi_user 得钥匙用户id
 * @property int $is_dujia 是否独家（0=否  1=是）
 * @property int $is_dujia_user 委托人
 * @property int $is_images 是否有图片
 * @property int $is_images_user 图片人
 * @property string $low_sell_price 出售低价
 * @property string $qianpei 签赔金
 * @property string $qianpei_img 签赔金图片
 * @property string $mark 标注
 * @property string $core_mark 核心标注
 * @property int $is_main 是否主推
 * @property int $is_fengpan 是否封盘(0=没封盘  1=意向金封盘 2=定金封盘）
 * @property string $fengpandaoqi 封盘到期时间
 * @property int $fengpan_user 封盘人
 * @property string $fengpan_image 封盘委托书
 * @property string $house_dengji 房源等级
 * @property string $quanyuangenjin 全员跟进时间
 * @property string $weihurengenjin 维护人跟进时间
 * @property string $daikanshijian 最后带看时间
 * @property int $genjincishu 跟进次数
 * @property int $daikancishu 带看次数
 * @property int $chuyong 是否出佣；0表示否，1表示是
 * @property string $private_company 维护人所在公司
 * @property int $private_store 维护人所在店
 * @property int $private_user 维护人
 * @property int $c_id 创建人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除
 * @property int $company_id 公司ID
 */
class HouseGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_type', 'house_status', 'house_private','dts_id', 'village_id', 'huxing_shi', 'huxing_ting', 'huxing_wei', 'huxing_chu', 'huxing_yangtai', 'louceng_now', 'louceng_total', 'tihu_ti', 'tihu_hu', 'is_yaoshi', 'is_yaoshi_user', 'is_dujia', 'is_dujia_user', 'is_images', 'is_images_user', 'is_main', 'is_fengpan', 'fengpan_user', 'genjincishu', 'daikancishu', 'chuyong', 'private_company', 'private_store', 'private_user', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['change_public_time', 'chanzhengriqi', 'fengpandaoqi', 'quanyuangenjin', 'weihurengenjin', 'daikanshijian', 'ctime', 'utime'], 'safe'],
            [['house_tag', 'house_tuijian_tag'], 'string'],
            [['sell_price', 'jianzhumianji', 'shiyongmianji', 'low_sell_price','qianpei'], 'number'],
            [['house_uuid'], 'string', 'max' => 50],
            [['house_sn', 'dts_name', 'village_name', 'loudong_name', 'danyuan_name', 'fanghao_name', 'customer_name', 'customer_sex', 'customer_phone', 'customer_type', 'house_title', 'house_school', 'chaoxiang', 'zhuangxiu', 'peitao', 'xianzhuang', 'fangwuleixing', 'jianzhujiegou', 'jianzaoniandai', 'chanquanxingzhi', 'chanquannianxian', 'fangyuanshuifei', 'kanfangfangshi', 'laiyuan', 'weituobianhao', 'fukuanfangshi', 'yaoshi_dian', 'mark', 'core_mark', 'fengpan_image', 'house_dengji','qianpei_img'], 'string', 'max' => 255],
            [['house_level'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'house_id' => 'House ID',
            'house_uuid' => '房源uuid',
            'sale_type' => '交易类型 1=二手出租 2=出售 3=高端出售',
            'house_sn' => '房源编号',
            'house_level' => '房源等级',
            'change_public_time' => '提供时间',
            'house_status' => '房源状态1=有效 2=成交 3=无效',
            'house_private' => '公盘私盘 1=共享盘 2=店公盘 3=公司公盘 4=公盘',
            'house_school' => '房源学区',
            'dts_id' => '片区区ID',
            'dts_name' => '片区名称',
            'village_id' => '小区ID',
            'village_name' => '小区名称',
            'loudong_name' => '楼栋',
            'danyuan_name' => '单元',
            'fanghao_name' => '房号',
            'customer_name' => '客户姓名',
            'customer_sex' => '客户性别',
            'customer_phone' => '客户电话',
            'customer_type' => '客户类型',
            'house_title' => '房源标题',
            'house_tag' => '房源标签',
            'house_tuijian_tag' => '房源推荐标签',
            'sell_price' => '售价',
            'huxing_shi' => '户型-室',
            'huxing_ting' => '户型-厅',
            'huxing_wei' => '户型-卫',
            'huxing_chu' => '户型-厨',
            'huxing_yangtai' => '户型-阳台',
            'jianzhumianji' => '建筑面积',
            'shiyongmianji' => '实用面积',
            'louceng_now' => '楼层-当前',
            'louceng_total' => '楼层-总共',
            'chaoxiang' => '朝向',
            'tihu_ti' => '梯户-梯',
            'tihu_hu' => '梯户-户',
            'zhuangxiu' => '装修',
            'peitao' => '配套',
            'xianzhuang' => '现状',
            'fangwuleixing' => '房屋类型',
            'jianzhujiegou' => '建筑结构',
            'jianzaoniandai' => '建造年代',
            'chanquanxingzhi' => '产权性质',
            'chanquannianxian' => '产权年限',
            'chanzhengriqi' => '产证日期',
            'fangyuanshuifei' => '房源税费',
            'kanfangfangshi' => '看房方式',
            'laiyuan' => '来源',
            'weituobianhao' => '委托编号',
            'fukuanfangshi' => '付款方式',
            'yaoshi_dian' => '钥匙店',
            'is_yaoshi' => '是否有钥匙 0=没有  1=有',
            'is_yaoshi_user' => '得钥匙用户id',
            'is_dujia' => '是否独家（0=否  1=是）',
            'is_dujia_user' => '委托人',
            'is_images' => '是否有图片',
            'is_images_user' => '图片人',
            'low_sell_price' => '出售低价',
            'qianpei' => '签赔金',
            'qianpei_img' => '签赔金图片',
            'mark' => '标注',
            'core_mark' => '核心标注',
            'is_main' => '是否主推',
            'is_fengpan' => '是否封盘(0=没封盘  1=意向金封盘 2=定金封盘）',
            'fengpandaoqi' => '封盘到期时间',
            'fengpan_user' => '封盘人',
            'fengpan_image' => '封盘委托书',
            'house_dengji' => '房源等级',
            'quanyuangenjin' => '全员跟进时间',
            'weihurengenjin' => '维护人跟进时间',
            'daikanshijian' => '最后带看时间',
            'genjincishu' => '跟进次数',
            'daikancishu' => '带看次数',
            'chuyong' => '是否出佣；0表示否，1表示是',
            'private_company' => '维护人所在公司',
            'private_store' => '维护人所在店',
            'private_user' => '维护人',
            'c_id' => '创建人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除',
            'company_id' => '公司ID',
        ];
    }
}
