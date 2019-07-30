<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_user".
 *
 * @property string $u_id 用户id
 * @property string $u_uuid 用户uuid
 * @property string $u_employee_prefix 员工编号前缀
 * @property string $u_employee_no 员工编号id
 * @property string $u_employee_id 员工编号
 * @property string $u_name 用户名
 * @property string $u_phone 用户手机号
 * @property string $u_account 用户账号
 * @property string $u_salt 密码salt
 * @property string $u_password 用户密码
 * @property int $u_sex 1男 2女
 * @property int $company_id 所属公司id
 * @property int $u_dept_id 部门id
 * @property int $u_role_id 角色id
 * @property string $u_lastip 用户最后登录ip
 * @property string $u_lasttime 用户登录时间
 * @property string $u_birthday_time 生日
 * @property string $u_entry_time 入职时间
 * @property string $u_identity 身份证
 * @property string $u_email 电子邮箱
 * @property int $u_introducer_id 介绍人
 * @property int $u_sort 排序
 * @property int $u_status 1正常，2休假，3锁定，4离职，5开除
 * @property string $u_head_url 用户头像
 * @property string $u_wx 微信
 * @property string $u_address 地址
 * @property double $jibengongzi 基本工资(元)
 * @property double $wuxiangeren 五险(个人)
 * @property double $wuxianyijingeren 五险一金(个人)
 * @property int $ticheng_id 个人提成id
 * @property string $ticheng 个人提成层级
 * @property int $fuzerenticheng_id 管理店提成id
 * @property string $fuzerenticheng 管理店提成层级
 * @property string $fuzerenticheng_zy 直营店提成方案层级
 * @property int $fuzerenticheng_zyid 直营店提成id
 * @property int $cid 创建人
 * @property int $uid 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $auth_uid 用户ID
 * @property int $auth_rid 组ID
 * @property int $auth_sid 店ID
 * @property int $auth_aid 区ID
 * @property int $auth_baid 大区iD
 * @property int $auth_cid 公司ID
 * @property int $is_del 是否删除（0=否  1=是）
 */
class UserGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['u_sex', 'company_id', 'u_dept_id', 'u_role_id', 'u_introducer_id', 'u_sort', 'u_status', 'ticheng_id', 'fuzerenticheng_id', 'fuzerenticheng_zyid', 'cid', 'uid', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del'], 'integer'],
            [['u_lasttime', 'u_birthday_time', 'u_entry_time', 'ctime', 'utime'], 'safe'],
            [['jibengongzi', 'wuxiangeren', 'wuxianyijingeren'], 'number'],
            [['u_uuid', 'u_employee_id', 'u_name', 'u_account', 'u_password', 'u_lastip', 'u_identity', 'u_email'], 'string', 'max' => 50],
            [['u_employee_prefix', 'u_employee_no'], 'string', 'max' => 20],
            [['u_phone'], 'string', 'max' => 11],
            [['u_salt'], 'string', 'max' => 5],
            [['u_head_url', 'u_wx', 'u_address', 'fuzerenticheng', 'fuzerenticheng_zy'], 'string', 'max' => 255],
            [['ticheng'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'u_id' => '用户id',
            'u_uuid' => '用户uuid',
            'u_employee_prefix' => '员工编号前缀',
            'u_employee_no' => '员工编号id',
            'u_employee_id' => '员工编号',
            'u_name' => '用户名',
            'u_phone' => '用户手机号',
            'u_account' => '用户账号',
            'u_salt' => '密码salt',
            'u_password' => '用户密码',
            'u_sex' => '1男 2女',
            'company_id' => '所属公司id',
            'u_dept_id' => '部门id',
            'u_role_id' => '角色id',
            'u_lastip' => '用户最后登录ip',
            'u_lasttime' => '用户登录时间',
            'u_birthday_time' => '生日',
            'u_entry_time' => '入职时间',
            'u_identity' => '身份证',
            'u_email' => '电子邮箱',
            'u_introducer_id' => '介绍人',
            'u_sort' => '排序',
            'u_status' => '1正常，2休假，3锁定，4离职，5开除',
            'u_head_url' => '用户头像',
            'u_wx' => '微信',
            'u_address' => '地址',
            'jibengongzi' => '基本工资(元)',
            'wuxiangeren' => '五险(个人)',
            'wuxianyijingeren' => '五险一金(个人)',
            'ticheng_id' => '个人提成id',
            'ticheng' => '个人提成层级',
            'fuzerenticheng_id' => '管理店提成id',
            'fuzerenticheng' => '管理店提成层级',
            'fuzerenticheng_zy' => '直营店提成方案层级',
            'fuzerenticheng_zyid' => '直营店提成id',
            'cid' => '创建人',
            'uid' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'auth_uid' => '用户ID',
            'auth_rid' => '组ID',
            'auth_sid' => '店ID',
            'auth_aid' => '区ID',
            'auth_baid' => '大区iD',
            'auth_cid' => '公司ID',
            'is_del' => '是否删除（0=否  1=是）',
        ];
    }
}
