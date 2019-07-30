<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_contractlist".
 *
 * @property int $con_id 合同id
 * @property string $bianhao 合同编号
 * @property string $mingcheng 合同名称
 * @property string $fy_bianhao 房源编号
 * @property string $ky_bianhao 客源编号
 * @property string $fy_khdh 房源客户电话
 * @property string $ky_khdh 客源客户电话
 * @property string $fy_khxm 房源客户姓名
 * @property string $ky_khxm 客源客户姓名
 * @property string $fy_xb 房源性别
 * @property string $ky_xb 客源性别
 * @property int $jingjiren 经纪人
 * @property int $bumen_id 部门id
 * @property string $zhuangtai 状态：进行中，审核中，报废，已签
 * @property string $htnr 合同内容
 * @property string $htqz 合同编号前缀
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
 * @property string $company_id 公司ID
 */
class ContractlistGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_contractlist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jingjiren', 'bumen_id', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del', 'company_id'], 'integer'],
            [['htnr'], 'string'],
            [['ctime', 'utime'], 'safe'],
            [['bianhao', 'mingcheng', 'fy_bianhao', 'ky_bianhao', 'fy_khdh', 'ky_khdh', 'fy_khxm', 'ky_khxm', 'zhuangtai', 'htqz'], 'string', 'max' => 50],
            [['fy_xb', 'ky_xb'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'con_id' => '合同id',
            'bianhao' => '合同编号',
            'mingcheng' => '合同名称',
            'fy_bianhao' => '房源编号',
            'ky_bianhao' => '客源编号',
            'fy_khdh' => '房源客户电话',
            'ky_khdh' => '客源客户电话',
            'fy_khxm' => '房源客户姓名',
            'ky_khxm' => '客源客户姓名',
            'fy_xb' => '房源性别',
            'ky_xb' => '客源性别',
            'jingjiren' => '经纪人',
            'bumen_id' => '部门id',
            'zhuangtai' => '状态：进行中，审核中，报废，已签',
            'htnr' => '合同内容',
            'htqz' => '合同编号前缀',
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
            'company_id' => '公司ID',
        ];
    }
}
