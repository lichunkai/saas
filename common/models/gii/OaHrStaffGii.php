<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "oa_hr_staff".
 *
 * @property int $hr_id 记录ID
 * @property int $staff_id 员工ID
 * @property string $staff_name 员工姓名
 * @property string $staff_no 员工编号
 * @property int $d_id 部门ID
 * @property int $sex 性别
 * @property string $alias 昵称
 * @property int $age 年龄
 * @property string $mingzu 民族
 * @property string $jiguan 籍贯
 * @property string $xueli 学历
 * @property string $hunyin 婚姻
 * @property string $hukou 户口
 * @property string $zhengzhimianmao 政治面貌
 * @property string $youzheng 邮政编码
 * @property string $birthday 出生日期
 * @property string $shenfenzheng 身份证号
 * @property string $ruzhishijian 入职日期
 * @property string $lianxifangshi 联系方式
 * @property string $suoshubumen 所属部门
 * @property string $zhiwu 职务
 * @property string $fenguanneirong 分管内容
 * @property string $jinjilianxiren 紧急联系人
 * @property string $jinjilianxirenphone 紧急联系人电话
 * @property string $zhaopinqudao 招聘渠道
 * @property string $hetongzhuangtai 合同状态
 * @property string $toubaozhuangtai 投保状态
 * @property string $zhuanzhengshijian 转正时间
 * @property string $yinhangkahao 银行卡号
 * @property string $jiatingzhuzhi 家庭住址
 * @property string $xianzhuzhi 现住址
 * @property string $gexingqianming 个性签名
 * @property int $c_id 添加人
 * @property int $u_id 修改人
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property int $is_del 0表示不删除，1表示删除
 * @property int $company_id 公司ID
 */
class OaHrStaffGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_hr_staff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_id', 'd_id', 'sex', 'age', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['staff_name', 'staff_no', 'alias', 'zhengzhimianmao', 'shenfenzheng', 'lianxifangshi', 'suoshubumen', 'zhiwu'], 'string', 'max' => 50],
            [['mingzu', 'jiguan', 'youzheng', 'birthday', 'ruzhishijian', 'jinjilianxiren', 'jinjilianxirenphone', 'zhaopinqudao', 'hetongzhuangtai', 'toubaozhuangtai', 'zhuanzhengshijian'], 'string', 'max' => 20],
            [['xueli', 'hunyin', 'hukou'], 'string', 'max' => 10],
            [['fenguanneirong', 'jiatingzhuzhi', 'xianzhuzhi', 'gexingqianming'], 'string', 'max' => 100],
            [['yinhangkahao'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hr_id' => '记录ID',
            'staff_id' => '员工ID',
            'staff_name' => '员工姓名',
            'staff_no' => '员工编号',
            'd_id' => '部门ID',
            'sex' => '性别',
            'alias' => '昵称',
            'age' => '年龄',
            'mingzu' => '民族',
            'jiguan' => '籍贯',
            'xueli' => '学历',
            'hunyin' => '婚姻',
            'hukou' => '户口',
            'zhengzhimianmao' => '政治面貌',
            'youzheng' => '邮政编码',
            'birthday' => '出生日期',
            'shenfenzheng' => '身份证号',
            'ruzhishijian' => '入职日期',
            'lianxifangshi' => '联系方式',
            'suoshubumen' => '所属部门',
            'zhiwu' => '职务',
            'fenguanneirong' => '分管内容',
            'jinjilianxiren' => '紧急联系人',
            'jinjilianxirenphone' => '紧急联系人电话',
            'zhaopinqudao' => '招聘渠道',
            'hetongzhuangtai' => '合同状态',
            'toubaozhuangtai' => '投保状态',
            'zhuanzhengshijian' => '转正时间',
            'yinhangkahao' => '银行卡号',
            'jiatingzhuzhi' => '家庭住址',
            'xianzhuzhi' => '现住址',
            'gexingqianming' => '个性签名',
            'c_id' => '添加人',
            'u_id' => '修改人',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'is_del' => '0表示不删除，1表示删除',
            'company_id' => '公司ID',
        ];
    }
}
