<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "com_district".
 *
 * @property int $dts_id 字段ID
 * @property int $province_id 省ID
 * @property string $province_name 省名称
 * @property int $city_id 市ID
 * @property string $city_name 市name
 * @property int $area_id 区ID
 * @property string $area_name
 * @property string $dts_name 片区名
 * @property string $dts_address 地址
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property int $company_id
 * @property int $dts_status 公用=0  私有=0
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除 0=没有删除  1=删除
 */
class ComDistrictGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'com_district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id', 'city_id', 'area_id', 'c_id', 'u_id', 'company_id', 'dts_status', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['province_name', 'city_name', 'area_name', 'dts_address'], 'string', 'max' => 255],
            [['dts_name'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dts_id' => '字段ID',
            'province_id' => '省ID',
            'province_name' => '省名称',
            'city_id' => '市ID',
            'city_name' => '市name',
            'area_id' => '区ID',
            'area_name' => 'Area Name',
            'dts_name' => '片区名',
            'dts_address' => '地址',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'company_id' => 'Company ID',
            'dts_status' => '公用=0  私有=0',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除 0=没有删除  1=删除',
        ];
    }
}
