<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "oa_kaoqing_tpl".
 *
 * @property int $kq_tp_id 考勤模板ID
 * @property string $kq_tp_name 模板名称
 * @property string $kq_tp_days 考勤天数选择
 * @property string $kq_tp_st 上班开始时间
 * @property string $kq_tp_ed 下班结束时间
 * @property int $is_def 1表示默认，0表示不默认
 * @property int $c_id 添加人ID
 * @property int $u_id 修改人ID
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property int $company_id 公司ID
 */
class OaKaoqingTplGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_kaoqing_tpl';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_def', 'c_id', 'u_id', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['kq_tp_name'], 'string', 'max' => 100],
            [['kq_tp_days'], 'string', 'max' => 50],
            [['kq_tp_st', 'kq_tp_ed'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kq_tp_id' => '考勤模板ID',
            'kq_tp_name' => '模板名称',
            'kq_tp_days' => '考勤天数选择',
            'kq_tp_st' => '上班开始时间',
            'kq_tp_ed' => '下班结束时间',
            'is_def' => '1表示默认，0表示不默认',
            'c_id' => '添加人ID',
            'u_id' => '修改人ID',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'company_id' => '公司ID',
        ];
    }
}
