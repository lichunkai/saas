<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_salary_config_mingcheng".
 *
 * @property int $scm_id 字段ID
 * @property string $fanganmingcheng 方案名称
 * @property string $fanganshuoming 方案说明
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
class Salary_config_mingchengGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_salary_config_mingcheng';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['fanganmingcheng'], 'string', 'max' => 100],
            [['fanganshuoming'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scm_id' => '字段ID',
            'fanganmingcheng' => '方案名称',
            'fanganshuoming' => '方案说明',
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
