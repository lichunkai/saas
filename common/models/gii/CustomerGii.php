<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_customer".
 *
 * @property int $customer_id 客户ID
 * @property string $customer_uuid 客户uuid
 * @property string $customer_private 公客私客
 * @property string $customer_name 客户姓名
 * @property int $customer_type 0求购，1租房
 * @property string $customer_sex 客户性别
 * @property string $tiaojianbiaoqian 条件标签
 * @property string $tiaojiandengji 条件等级
 * @property string $xuqiubianhao 需求编号
 * @property int $u_dept_id 部门id
 * @property int $dts_id 片区区ID
 * @property string $dts_name 片区名称
 * @property int $rn_id 小区ID
 * @property string $rn_name 小区名称
 * @property string $village
 * @property string $village_name
 * @property string $dengji
 * @property string $duoxuanbiaoqian 急切全款
 * @property string $yongtu 用途
 * @property string $xuqiuhuxing_min 需求户型-最大
 * @property string $xuqiuhuxing_max 需求户型-最小
 * @property string $xuqiujiage_min 需求价格-最小
 * @property string $xuqiujiage_max 需求价格-最大
 * @property string $xuqiumianji_min 需求面积-最小
 * @property string $xuqiumianji_max 需求面积-最大
 * @property string $xuqiulouceng_min 需求楼层-最小
 * @property string $xuqiulouceng_max 需求楼层-最大
 * @property int $daikancishu 带看次数
 * @property int $genjincishu 跟进次数
 * @property string $xuqiufangling_min 需求房龄-最小
 * @property string $xuqiufangling_max 需求房龄-最大
 * @property string $chaoxiang 朝向
 * @property string $zhuangxiu 装修
 * @property string $peitao 配套
 * @property string $xuqiuyuanying 需求原因
 * @property string $fangwuleixing 房屋类型
 * @property string $goutongjieduan 沟通阶段
 * @property string $kehulaiyuan 客户来源
 * @property string $xiaofeilinian 消费理念
 * @property string $guoji 国际
 * @property string $minzu 民族
 * @property string $zhengjianhaoma 证件号码
 * @property string $email 邮箱
 * @property string $qq qq
 * @property string $weixin 微信
 * @property string $jiaotonggongju 交通工具
 * @property string $chexing 车型
 * @property string $mark 备注
 * @property string $core_mark 核心备注
 * @property string $zhuangtai 状态
 * @property int $is_fengpan 是否封盘
 * @property string $fengpandaoqi 封盘时间
 * @property int $fengpan_user 封盘用户
 * @property string $quanyuangenjin 全员跟进时间
 * @property string $weihurengenjin 维护人跟进时间
 * @property string $daikanshijian 最后带看时间
 * @property string $xiading 下定
 * @property int $zhutui 主推 0为不推，1为主推
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $utime 更新时间
 * @property string $ctime
 * @property int $auth_uid 用户ID
 * @property int $auth_rid 组ID
 * @property int $auth_sid 店ID
 * @property int $auth_aid 区ID
 * @property int $auth_baid 大区ID
 * @property int $auth_cid
 * @property int $is_del 是否删除
 * @property string $company_id 公司ID
 */
class CustomerGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_type', 'u_dept_id', 'dts_id', 'rn_id', 'daikancishu', 'genjincishu', 'is_fengpan', 'fengpan_user', 'zhutui', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del', 'company_id'], 'integer'],
            [['mark', 'core_mark'], 'string'],
            [['fengpandaoqi', 'quanyuangenjin', 'weihurengenjin', 'daikanshijian', 'utime', 'ctime'], 'safe'],
            [['customer_uuid', 'dts_name', 'rn_name', 'village', 'village_name', 'xuqiulouceng_min', 'xuqiulouceng_max'], 'string', 'max' => 50],
            [['customer_private', 'tiaojiandengji'], 'string', 'max' => 100],
            [['customer_name', 'customer_sex', 'tiaojianbiaoqian', 'xuqiubianhao', 'dengji', 'duoxuanbiaoqian', 'yongtu', 'xuqiuhuxing_min', 'xuqiuhuxing_max', 'xuqiujiage_min', 'xuqiujiage_max', 'xuqiumianji_min', 'xuqiumianji_max', 'xuqiufangling_min', 'xuqiufangling_max', 'chaoxiang', 'zhuangxiu', 'peitao', 'xuqiuyuanying', 'fangwuleixing', 'goutongjieduan', 'kehulaiyuan', 'xiaofeilinian', 'guoji', 'minzu', 'zhengjianhaoma', 'email', 'qq', 'weixin', 'jiaotonggongju', 'chexing', 'zhuangtai'], 'string', 'max' => 255],
            [['xiading'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => '客户ID',
            'customer_uuid' => '客户uuid',
            'customer_private' => '公客私客',
            'customer_name' => '客户姓名',
            'customer_type' => '0求购，1租房',
            'customer_sex' => '客户性别',
            'tiaojianbiaoqian' => '条件标签',
            'tiaojiandengji' => '条件等级',
            'xuqiubianhao' => '需求编号',
            'u_dept_id' => '部门id',
            'dts_id' => '片区区ID',
            'dts_name' => '片区名称',
            'rn_id' => '小区ID',
            'rn_name' => '小区名称',
            'village' => 'Village',
            'village_name' => 'Village Name',
            'dengji' => 'Dengji',
            'duoxuanbiaoqian' => '急切全款',
            'yongtu' => '用途',
            'xuqiuhuxing_min' => '需求户型-最大',
            'xuqiuhuxing_max' => '需求户型-最小',
            'xuqiujiage_min' => '需求价格-最小',
            'xuqiujiage_max' => '需求价格-最大',
            'xuqiumianji_min' => '需求面积-最小',
            'xuqiumianji_max' => '需求面积-最大',
            'xuqiulouceng_min' => '需求楼层-最小',
            'xuqiulouceng_max' => '需求楼层-最大',
            'daikancishu' => '带看次数',
            'genjincishu' => '跟进次数',
            'xuqiufangling_min' => '需求房龄-最小',
            'xuqiufangling_max' => '需求房龄-最大',
            'chaoxiang' => '朝向',
            'zhuangxiu' => '装修',
            'peitao' => '配套',
            'xuqiuyuanying' => '需求原因',
            'fangwuleixing' => '房屋类型',
            'goutongjieduan' => '沟通阶段',
            'kehulaiyuan' => '客户来源',
            'xiaofeilinian' => '消费理念',
            'guoji' => '国际',
            'minzu' => '民族',
            'zhengjianhaoma' => '证件号码',
            'email' => '邮箱',
            'qq' => 'qq',
            'weixin' => '微信',
            'jiaotonggongju' => '交通工具',
            'chexing' => '车型',
            'mark' => '备注',
            'core_mark' => '核心备注',
            'zhuangtai' => '状态',
            'is_fengpan' => '是否封盘',
            'fengpandaoqi' => '封盘时间',
            'fengpan_user' => '封盘用户',
            'quanyuangenjin' => '全员跟进时间',
            'weihurengenjin' => '维护人跟进时间',
            'daikanshijian' => '最后带看时间',
            'xiading' => '下定',
            'zhutui' => '主推 0为不推，1为主推',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'utime' => '更新时间',
            'ctime' => 'Ctime',
            'auth_uid' => '用户ID',
            'auth_rid' => '组ID',
            'auth_sid' => '店ID',
            'auth_aid' => '区ID',
            'auth_baid' => '大区ID',
            'auth_cid' => 'Auth Cid',
            'is_del' => '是否删除',
            'company_id' => '公司ID',
        ];
    }
}
