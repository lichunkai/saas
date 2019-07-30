<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "oa_kaoqing_user_setting".
 *
 * @property string $kq_user_id 考勤员工设置ID
 * @property int $kq_tp_id 模板ID
 * @property int $d_id 部门ID
 * @property int $u_id 员工ID
 * @property string $kq_date 排班日期
 * @property string $kq_md 日期
 * @property string $kq_st 上班开始时间
 * @property string $kq_ed 下班时间
 * @property string $ctime 删除时间
 * @property string $utime 修改时间
 * @property int $company_id 公司ID
 */
class OaKaoqingUserSettingGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_kaoqing_user_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kq_tp_id', 'd_id', 'u_id', 'company_id'], 'integer'],
            [['kq_date', 'ctime', 'utime'], 'safe'],
            [['kq_md', 'kq_st', 'kq_ed'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kq_user_id' => '考勤员工设置ID',
            'kq_tp_id' => '模板ID',
            'd_id' => '部门ID',
            'u_id' => '员工ID',
            'kq_date' => '排班日期',
            'kq_md' => '日期',
            'kq_st' => '上班开始时间',
            'kq_ed' => '下班时间',
            'ctime' => '删除时间',
            'utime' => '修改时间',
            'company_id' => '公司ID',
        ];
    }
}
