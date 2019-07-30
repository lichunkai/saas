<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_verify".
 *
 * @property string $v_id 审核ID
 * @property int $v_post_user 提交人
 * @property int $v_end_user 审核人
 * @property int $v_service_type 业务类型（1=房源 2=客源 3=成交）
 * @property string $v_type 审核类型
 * @property int $v_status 审核状态（0=等待审核 1=通过 9=驳回）
 * @property string $v_reject_reason 驳回原因
 * @property string $v_end_reason 处理方式
 * @property string $v_content 审核内容
 * @property string $v_service_sn 资源编号
 * @property string $v_service_id 资源ID
 * @property string $v_pass_func 通过后执行的方法
 * @property string $v_pass_param 能过后方法参数
 * @property string $v_reject_func 驳回后执行的方法
 * @property string $v_reject_param 驳回后执行的方法参数
 * @property int $c_id 创建人
 * @property int $u_id 操作人
 * @property string $ctime 创建事件
 * @property string $utime 更新事件
 * @property int $is_del 是否删除
 * @property string $company_id 公司ID
 */
class VerifyGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_verify';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['v_post_user'], 'required'],
            [['v_post_user', 'v_end_user', 'v_service_type', 'v_status', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['v_pass_func', 'v_pass_param', 'v_reject_func', 'v_reject_param'], 'string'],
            [['ctime', 'utime'], 'safe'],
            [['v_type', 'v_reject_reason', 'v_end_reason', 'v_content', 'v_service_sn'], 'string', 'max' => 255],
            [['v_service_id'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'v_id' => '审核ID',
            'v_post_user' => '提交人',
            'v_end_user' => '审核人',
            'v_service_type' => '业务类型（1=房源 2=客源 3=成交）',
            'v_type' => '审核类型',
            'v_status' => '审核状态（0=等待审核 1=通过 9=驳回）',
            'v_reject_reason' => '驳回原因',
            'v_end_reason' => '处理方式',
            'v_content' => '审核内容',
            'v_service_sn' => '资源编号',
            'v_service_id' => '资源ID',
            'v_pass_func' => '通过后执行的方法',
            'v_pass_param' => '能过后方法参数',
            'v_reject_func' => '驳回后执行的方法',
            'v_reject_param' => '驳回后执行的方法参数',
            'c_id' => '创建人',
            'u_id' => '操作人',
            'ctime' => '创建事件',
            'utime' => '更新事件',
            'is_del' => '是否删除',
            'company_id' => '公司ID',
        ];
    }
}
