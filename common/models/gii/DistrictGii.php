<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_district".
 *
 * @property int $dt_id 字段ID
 * @property int $dt_province_id 省id
 * @property string $dt_province_name 省名
 * @property int $dt_city_id 城市id
 * @property string $dt_city_name 城市名
 * @property int $dt_area_id 区域id
 * @property string $dt_area_name 区域名
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
class DistrictGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt_province_id', 'dt_city_id', 'dt_area_id', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['dt_province_name', 'dt_city_name', 'dt_area_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dt_id' => '字段ID',
            'dt_province_id' => '省id',
            'dt_province_name' => '省名',
            'dt_city_id' => '城市id',
            'dt_city_name' => '城市名',
            'dt_area_id' => '区域id',
            'dt_area_name' => '区域名',
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
