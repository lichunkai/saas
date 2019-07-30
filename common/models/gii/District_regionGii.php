<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_district_region".
 *
 * @property int $rn_id 字段ID
 * @property string $rn_name 小区名称
 * @property int $dt_id 行政区id
 * @property int $dts_id 片区id
 * @property string $rn_price 出售均价
 * @property string $rn_address 小区地址
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
class District_regionGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_district_region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt_id', 'dts_id', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del', 'company_id'], 'integer'],
            [['rn_price'], 'number'],
            [['ctime', 'utime'], 'safe'],
            [['rn_name'], 'string', 'max' => 100],
            [['rn_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rn_id' => '字段ID',
            'rn_name' => '小区名称',
            'dt_id' => '行政区id',
            'dts_id' => '片区id',
            'rn_price' => '出售均价',
            'rn_address' => '小区地址',
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
