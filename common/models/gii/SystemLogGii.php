<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_system_log".
 *
 * @property string $log_id 自增id
 * @property string $log_url 请求地址
 * @property string $log_desp 请求描述
 * @property string $log_param 请求提交的参数
 * @property string $log_ip 请求的IP地址
 * @property int $log_uid 操作人id
 * @property string $log_uname 操作人名
 * @property int $depart_id 部门id
 * @property int $role_id 角色id
 * @property string $ctime 添加时间
 * @property int $is_del 是否删除
 * @property string $company_id 公司ID
 */
class SystemLogGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_system_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_param'], 'string'],
            [['log_uid', 'depart_id', 'role_id', 'is_del', 'company_id'], 'integer'],
            [['ctime'], 'safe'],
            [['log_url', 'log_desp', 'log_ip'], 'string', 'max' => 255],
            [['log_uname'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => '自增id',
            'log_url' => '请求地址',
            'log_desp' => '请求描述',
            'log_param' => '请求提交的参数',
            'log_ip' => '请求的IP地址',
            'log_uid' => '操作人id',
            'log_uname' => '操作人名',
            'depart_id' => '部门id',
            'role_id' => '角色id',
            'ctime' => '添加时间',
            'is_del' => '是否删除',
            'company_id' => '公司ID',
        ];
    }
}
