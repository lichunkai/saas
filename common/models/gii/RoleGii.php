<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_role".
 *
 * @property string $role_id 角色ID/唯一/自增
 * @property string $role_name 角色描述
 * @property int $role_type 角色类型
 * @property int $company_id 所属公司id
 * @property string $role_desp 角色描述
 * @property int $cid 创建人
 * @property int $uid 修改人
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property int $auth_uid 用户ID
 * @property int $auth_rid 组ID
 * @property int $auth_sid 店ID
 * @property int $auth_aid 区ID
 * @property int $auth_baid 大区iD
 * @property int $auth_cid 公司ID
 * @property int $is_del 0表示不删除，1表示删除
 */
class RoleGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_type', 'company_id', 'cid', 'uid', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['role_name'], 'string', 'max' => 50],
            [['role_desp'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => '角色ID/唯一/自增',
            'role_name' => '角色描述',
            'role_type' => '角色类型',
            'company_id' => '所属公司id',
            'role_desp' => '角色描述',
            'cid' => '创建人',
            'uid' => '修改人',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'auth_uid' => '用户ID',
            'auth_rid' => '组ID',
            'auth_sid' => '店ID',
            'auth_aid' => '区ID',
            'auth_baid' => '大区iD',
            'auth_cid' => '公司ID',
            'is_del' => '0表示不删除，1表示删除',
        ];
    }
}
