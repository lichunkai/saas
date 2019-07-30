<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_depart".
 *
 * @property string $d_id 组织ID
 * @property string $d_name 组织名称
 * @property int $d_type 部门类型
 * @property int $company_id 所属公司id
 * @property int $d_principal_id 主管id
 * @property string $d_principal 负责人
 * @property int $d_district 部门区域
 * @property string $d_address 部门地址
 * @property string $d_phone 部门电话
 * @property int $d_pid 父级ID
 * @property string $d_pid_name 部门名称
 * @property string $d_location 坐标，跟据地址生成
 * @property int $d_sort 排序
 * @property int $d_level 等级
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
class DepartGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_depart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['d_type', 'company_id', 'd_principal_id', 'd_district', 'd_pid', 'd_sort', 'd_level', 'cid', 'uid', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['d_name', 'd_pid_name', 'd_location'], 'string', 'max' => 50],
            [['d_principal'], 'string', 'max' => 30],
            [['d_address'], 'string', 'max' => 100],
            [['d_phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'd_id' => '组织ID',
            'd_name' => '组织名称',
            'd_type' => '部门类型',
            'company_id' => '所属公司id',
            'd_principal_id' => '主管id',
            'd_principal' => '负责人',
            'd_district' => '部门区域',
            'd_address' => '部门地址',
            'd_phone' => '部门电话',
            'd_pid' => '父级ID',
            'd_pid_name' => '部门名称',
            'd_location' => '坐标，跟据地址生成',
            'd_sort' => '排序',
            'd_level' => '等级',
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
