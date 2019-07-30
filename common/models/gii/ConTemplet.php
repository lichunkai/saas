<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_con_templet".
 *
 * @property int $con_templet_id 模板ID
 * @property string $con_templet_type 模板类新
 * @property string $con_templet_content
 * @property string $con_templet_status
 * @property string $con_templet_prefix
 * @property string $con_templet_name
 * @property int $is_verify 是否需要审核
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 添加时间
 * @property string $utime 更新时间
 * @property int $auth_uid 用户ID
 * @property int $auth_rid 组ID
 * @property int $auth_sid 店ID
 * @property int $auth_aid 区ID
 * @property int $auth_baid 大区ID
 * @property int $is_del 是否删除
 * @property string $company_id 公司ID
 */
class ConTemplet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_con_templet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['con_templet_content'], 'string'],
            [['is_verify', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['con_templet_type', 'con_templet_status', 'con_templet_prefix', 'con_templet_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'con_templet_id' => '模板ID',
            'con_templet_type' => '模板类新',
            'con_templet_content' => 'Con Templet Content',
            'con_templet_status' => 'Con Templet Status',
            'con_templet_prefix' => 'Con Templet Prefix',
            'con_templet_name' => 'Con Templet Name',
            'is_verify' => '是否需要审核',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '添加时间',
            'utime' => '更新时间',
            'auth_uid' => '用户ID',
            'auth_rid' => '组ID',
            'auth_sid' => '店ID',
            'auth_aid' => '区ID',
            'auth_baid' => '大区ID',
            'is_del' => '是否删除',
            'company_id' => '公司ID',
        ];
    }
}
