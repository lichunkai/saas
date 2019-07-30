<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_salary_config_biandong".
 *
 * @property int $scb_id 字段ID
 * @property int $bumen_id 部门id
 * @property int $renyuan 人员
 * @property string $biandongleixing 变动类型
 * @property string $zhengjianleixing 增减类型
 * @property double $jine 金额
 * @property string $beizhu 备注
 * @property string $biandongriqi 变动日期
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
class Salary_config_biandongGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_salary_config_biandong';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bumen_id', 'renyuan', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del', 'company_id'], 'integer'],
            [['jine'], 'number'],
            [['biandongriqi', 'ctime', 'utime'], 'safe'],
            [['biandongleixing', 'zhengjianleixing'], 'string', 'max' => 100],
            [['beizhu'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scb_id' => '字段ID',
            'bumen_id' => '部门id',
            'renyuan' => '人员',
            'biandongleixing' => '变动类型',
            'zhengjianleixing' => '增减类型',
            'jine' => '金额',
            'beizhu' => '备注',
            'biandongriqi' => '变动日期',
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
