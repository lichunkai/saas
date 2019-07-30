<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "oa_qingjia".
 *
 * @property int $oa_qingjia_id 请假ID
 * @property int $staff_id 员工ID
 * @property int $d_id 员工部门
 * @property string $shenqing_date 申请日期
 * @property string $st_time 开始时间
 * @property string $ed_time 结束时间
 * @property string $sh_time 审核时间
 * @property string $type 请假类型
 * @property int $status 状态，0表示待确认，1表示通过，2表示驳回
 * @property int $shenhe_id 审核人
 * @property int $tixing_id 提醒人
 * @property string $remark 备注
 * @property int $c_id 添加人
 * @property int $u_id 修改人
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property int $is_del 0表示不删除，1表示删除
 * @property int $company_id 公司ID
 */
class OaQingjiaGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_qingjia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_id', 'd_id', 'status', 'shenhe_id', 'tixing_id', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['shenqing_date', 'st_time', 'ed_time', 'sh_time', 'ctime', 'utime'], 'safe'],
            [['type'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oa_qingjia_id' => '请假ID',
            'staff_id' => '员工ID',
            'd_id' => '员工部门',
            'shenqing_date' => '申请日期',
            'st_time' => '开始时间',
            'ed_time' => '结束时间',
            'sh_time' => '审核时间',
            'type' => '请假类型',
            'status' => '状态，0表示待确认，1表示通过，2表示驳回',
            'shenhe_id' => '审核人',
            'tixing_id' => '提醒人',
            'remark' => '备注',
            'c_id' => '添加人',
            'u_id' => '修改人',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'is_del' => '0表示不删除，1表示删除',
            'company_id' => '公司ID',
        ];
    }
}
