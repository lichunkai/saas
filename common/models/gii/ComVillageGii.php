<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "com_village".
 *
 * @property int $village_id 字段ID
 * @property string $village_name 小区名称
 * @property int $dts_id 片区ID
 * @property string $dts_name 片区名称
 * @property string $village_price 出售均价
 * @property string $village_address 小区地址
 * @property string $village_jctime
 * @property string $village_wygs
 * @property string $village_kfs
 * @property string $village_wyf
 * @property int $village_ldzs
 * @property int $village_fwzs
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $village_status 公用=0 私有=1
 * @property string $company_id 公司ID
 * @property int $is_del 是否删除 0=没有删除  1=删除
 */
class ComVillageGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'com_village';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dts_id', 'village_ldzs', 'village_fwzs', 'c_id', 'u_id', 'village_status', 'company_id', 'is_del'], 'integer'],
            [['village_price'], 'number'],
            [['ctime', 'utime'], 'safe'],
            [['village_name'], 'string', 'max' => 100],
            [['dts_name', 'village_address', 'village_jctime', 'village_wygs', 'village_kfs', 'village_wyf'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'village_id' => '字段ID',
            'village_name' => '小区名称',
            'dts_id' => '片区ID',
            'dts_name' => '片区名称',
            'village_price' => '出售均价',
            'village_address' => '小区地址',
            'village_jctime' => 'Village Jctime',
            'village_wygs' => 'Village Wygs',
            'village_kfs' => 'Village Kfs',
            'village_wyf' => 'Village Wyf',
            'village_ldzs' => 'Village Ldzs',
            'village_fwzs' => 'Village Fwzs',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'village_status' => '公用=0 私有=1',
            'company_id' => '公司ID',
            'is_del' => '是否删除 0=没有删除  1=删除',
        ];
    }
}
