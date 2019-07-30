<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "oa_kaoqing_guanli".
 *
 * @property int $kq_mg_id 考勤管理ID
 * @property int $staff_id 员工ID
 * @property int $d_id 部门ID
 * @property string $kq_date 日期
 * @property string $status 状态
 * @property string $tp_name 模板名称
 * @property string $df_st 默认上班时间
 * @property string $df_ed 默认下班时间
 * @property string $sj_st 实际上班时间
 * @property string $st_lat_lng 上班经纬度
 * @property string $sj_ed 实际下班时间
 * @property string $ed_lat_lng 下班经纬度
 * @property string $st_photo 上班卡图
 * @property string $ed_photo 下班卡图
 * @property string $st_content 上班外出说明
 * @property string $ed_content 下班外出说明
 * @property string $remark 备注
 * @property int $flag 0:未打卡,1:上班未打卡,2:下班未打卡,3:迟到下班未打卡,4:上班未打卡早退,5:迟到,6:迟到早退,7:早退,8:休息,9:正常
 * @property int $st_waichu 0表示公司打卡，1表示外出打卡
 * @property int $ed_waichu 0表示公司打卡，1表示外出打卡
 * @property int $u_id 修改考勤人
 * @property string $ctime 添加人
 * @property string $utime 修改人
 * @property int $is_del 0表示不删除，1表示删除
 * @property int $company_id 公司ID
 */
class OaKaoqingGuanliGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_kaoqing_guanli';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_id', 'd_id', 'flag', 'st_waichu', 'ed_waichu', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['kq_date', 'ctime', 'utime'], 'safe'],
            [['status'], 'string', 'max' => 10],
            [['tp_name', 'st_lat_lng', 'ed_lat_lng'], 'string', 'max' => 50],
            [['df_st', 'df_ed', 'sj_st', 'sj_ed'], 'string', 'max' => 20],
            [['st_photo', 'ed_photo', 'st_content', 'ed_content', 'remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kq_mg_id' => '考勤管理ID',
            'staff_id' => '员工ID',
            'd_id' => '部门ID',
            'kq_date' => '日期',
            'status' => '状态',
            'tp_name' => '模板名称',
            'df_st' => '默认上班时间',
            'df_ed' => '默认下班时间',
            'sj_st' => '实际上班时间',
            'st_lat_lng' => '上班经纬度',
            'sj_ed' => '实际下班时间',
            'ed_lat_lng' => '下班经纬度',
            'st_photo' => '上班卡图',
            'ed_photo' => '下班卡图',
            'st_content' => '上班外出说明',
            'ed_content' => '下班外出说明',
            'remark' => '备注',
            'flag' => '0:未打卡,1:上班未打卡,2:下班未打卡,3:迟到下班未打卡,4:上班未打卡早退,5:迟到,6:迟到早退,7:早退,8:休息,9:正常',
            'st_waichu' => '0表示公司打卡，1表示外出打卡',
            'ed_waichu' => '0表示公司打卡，1表示外出打卡',
            'u_id' => '修改考勤人',
            'ctime' => '添加人',
            'utime' => '修改人',
            'is_del' => '0表示不删除，1表示删除',
            'company_id' => '公司ID',
        ];
    }
}
