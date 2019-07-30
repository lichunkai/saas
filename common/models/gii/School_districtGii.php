<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_school_district".
 *
 * @property int $sd_id 字段ID
 * @property int $rn_id 小区id
 * @property string $beizhu 备注
 * @property int $s_id 学区id
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $uid 用户ID
 * @property int $rid 组ID
 * @property int $sid 店ID
 * @property int $aid 区ID
 * @property int $baid 大区ID
 * @property int $cid 公司ID
 * @property int $is_del 是否删除 0=没有删除  1=删除
 * @property string $company_id 公司ID
 */
class School_districtGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_school_district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rn_id', 's_id', 'c_id', 'u_id', 'uid', 'rid', 'sid', 'aid', 'baid', 'cid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['beizhu'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sd_id' => '字段ID',
            'rn_id' => '小区id',
            'beizhu' => '备注',
            's_id' => '学区id',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'uid' => '用户ID',
            'rid' => '组ID',
            'sid' => '店ID',
            'aid' => '区ID',
            'baid' => '大区ID',
            'cid' => '公司ID',
            'is_del' => '是否删除 0=没有删除  1=删除',
            'company_id' => '公司ID',
        ];
    }
}
