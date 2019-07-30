<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "company_shop_house".
 *
 * @property string $house_id 房源ID
 * @property int $erp_house_id 对应erp房源id
 * @property string $house_no 房源编号
 * @property string $house_photo 封面
 * @property string $loupan_name 楼盘名称
 * @property string $district 区域
 * @property string $address 地址
 * @property string $fangyuan_location 房源经纬度
 * @property string $chaoxiang 朝向
 * @property string $shangquan 商圈
 * @property double $mianji 建筑面积
 * @property string $yaoshi 钥匙
 * @property int $tuijian 是否推荐1推荐0不推荐
 * @property string $zhuangxiu 装修
 * @property string $wuyeleixing 物业类型
 * @property string $zuoluo 坐落
 * @property string $zonglouceng 总楼层
 * @property string $huxing_shi 户型-室
 * @property string $huxing_ting 户型-厅
 * @property string $huxing_wei 户型-卫
 * @property string $py_title 标题
 * @property string $py_desp 描述
 * @property string $py_type 盘源类型
 * @property string $house_stage_jieduan 房源跟进阶段
 * @property string $house_stage_time 跟进阶段更新时间
 * @property string $py_suoshu_type 所属类型
 * @property string $py_chanquan 产权性质
 * @property string $py_chanquan_mianji 产权面积
 * @property double $py_es_shoujia 二手房售价
 * @property double $py_es_danjia 二手房单价
 * @property string $py_es_chanquanxz 产权现状
 * @property string $py_es_fangling 房龄
 * @property string $py_es_sfzz 税费政策
 * @property string $py_es_fkfs 付款方式
 * @property string $py_zf_zujin 租金
 * @property string $py_zf_fkfs 付款方式
 * @property string $py_zf_yjyq 押金要求
 * @property string $py_zf_js 家私
 * @property string $py_zf_qzlx 求租类型
 * @property string $yz_name 业主姓名
 * @property string $yz_phone 联系电话
 * @property string $yz_shenfengzhen 身份证
 * @property string $other_wtfs 委托方式
 * @property string $other_fczbh 房产证编号
 * @property string $other_wtbh 委托编号
 * @property string $other_fczmc 房产证名称
 * @property string $other_tags 标签
 * @property int $house_status 房源状态1是上架0下架
 * @property int $house_type 0表示二手房，1表示租房，2表示新房
 * @property int $house_flag 0表示公房，1表示私房
 * @property int $house_member_id 所属经纪人
 * @property int $kefu_id 客服ID
 * @property int $shop_id 店铺ID
 * @property int $company_id 机构ID
 * @property int $cid 添加人
 * @property int $uid 修改人
 * @property string $ctime 新建时间
 * @property string $utime 修改时间
 * @property int $is_del 0表示不删除，1表示删除
 */
class CompanyShopHouseGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_shop_house';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('web_db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['erp_house_id', 'tuijian', 'house_status', 'house_type', 'house_flag', 'house_member_id', 'kefu_id', 'shop_id', 'company_id', 'cid', 'uid', 'is_del'], 'integer'],
            [['mianji', 'py_es_shoujia', 'py_es_danjia'], 'number'],
            [['house_stage_time', 'ctime', 'utime'], 'safe'],
            [['house_no', 'yaoshi', 'zonglouceng', 'py_type', 'py_chanquan_mianji', 'py_es_fkfs', 'py_zf_zujin', 'py_zf_fkfs', 'py_zf_yjyq', 'py_zf_qzlx', 'yz_name', 'yz_phone'], 'string', 'max' => 20],
            [['house_photo', 'loupan_name', 'house_stage_jieduan'], 'string', 'max' => 100],
            [['district', 'fangyuan_location', 'wuyeleixing', 'zuoluo', 'py_suoshu_type', 'py_chanquan', 'other_wtfs', 'other_fczbh', 'other_fczmc'], 'string', 'max' => 50],
            [['address', 'py_desp', 'py_zf_js', 'other_tags'], 'string', 'max' => 255],
            [['chaoxiang', 'shangquan', 'zhuangxiu', 'py_es_chanquanxz', 'py_es_sfzz', 'yz_shenfengzhen'], 'string', 'max' => 30],
            [['huxing_shi', 'huxing_ting', 'huxing_wei'], 'string', 'max' => 5],
            [['py_title'], 'string', 'max' => 200],
            [['py_es_fangling'], 'string', 'max' => 10],
            [['other_wtbh'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'house_id' => '房源ID',
            'erp_house_id' => '对应erp房源id',
            'house_no' => '房源编号',
            'house_photo' => '封面',
            'loupan_name' => '楼盘名称',
            'district' => '区域',
            'address' => '地址',
            'fangyuan_location' => '房源经纬度',
            'chaoxiang' => '朝向',
            'shangquan' => '商圈',
            'mianji' => '建筑面积',
            'yaoshi' => '钥匙',
            'tuijian' => '是否推荐1推荐0不推荐',
            'zhuangxiu' => '装修',
            'wuyeleixing' => '物业类型',
            'zuoluo' => '坐落',
            'zonglouceng' => '总楼层',
            'huxing_shi' => '户型-室',
            'huxing_ting' => '户型-厅',
            'huxing_wei' => '户型-卫',
            'py_title' => '标题',
            'py_desp' => '描述',
            'py_type' => '盘源类型',
            'house_stage_jieduan' => '房源跟进阶段',
            'house_stage_time' => '跟进阶段更新时间',
            'py_suoshu_type' => '所属类型',
            'py_chanquan' => '产权性质',
            'py_chanquan_mianji' => '产权面积',
            'py_es_shoujia' => '二手房售价',
            'py_es_danjia' => '二手房单价',
            'py_es_chanquanxz' => '产权现状',
            'py_es_fangling' => '房龄',
            'py_es_sfzz' => '税费政策',
            'py_es_fkfs' => '付款方式',
            'py_zf_zujin' => '租金',
            'py_zf_fkfs' => '付款方式',
            'py_zf_yjyq' => '押金要求',
            'py_zf_js' => '家私',
            'py_zf_qzlx' => '求租类型',
            'yz_name' => '业主姓名',
            'yz_phone' => '联系电话',
            'yz_shenfengzhen' => '身份证',
            'other_wtfs' => '委托方式',
            'other_fczbh' => '房产证编号',
            'other_wtbh' => '委托编号',
            'other_fczmc' => '房产证名称',
            'other_tags' => '标签',
            'house_status' => '房源状态1是上架0下架',
            'house_type' => '0表示二手房，1表示租房，2表示新房',
            'house_flag' => '0表示公房，1表示私房',
            'house_member_id' => '所属经纪人',
            'kefu_id' => '客服ID',
            'shop_id' => '店铺ID',
            'company_id' => '机构ID',
            'cid' => '添加人',
            'uid' => '修改人',
            'ctime' => '新建时间',
            'utime' => '修改时间',
            'is_del' => '0表示不删除，1表示删除',
        ];
    }
}
