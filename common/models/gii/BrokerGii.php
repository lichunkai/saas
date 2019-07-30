<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_broker".
 *
 * @property int $b_id
 * @property string $b_name 经纪人姓名
 * @property string $b_phone 经纪人电话
 * @property int $b_province 省
 * @property int $b_city 市
 * @property int $b_area 区
 * @property string $b_address 经纪人详细地址
 * @property int $cid
 * @property int $uid
 * @property string $ctime
 * @property string $utime
 * @property int $is_del 0正常 1不正常
 * @property string $b_province_name 省名字
 * @property string $b_city_name 城市名字
 * @property string $b_area_name 区域名字
 */
class BrokerGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_broker';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['b_province', 'b_city', 'b_area', 'cid', 'uid', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['b_name', 'b_phone', 'b_province_name', 'b_city_name', 'b_area_name'], 'string', 'max' => 50],
            [['b_address'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'b_id' => 'B ID',
            'b_name' => '经纪人姓名',
            'b_phone' => '经纪人电话',
            'b_province' => '省',
            'b_city' => '市',
            'b_area' => '区',
            'b_address' => '经纪人详细地址',
            'cid' => 'Cid',
            'uid' => 'Uid',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => '0正常 1不正常',
            'b_province_name' => '省名字',
            'b_city_name' => '城市名字',
            'b_area_name' => '区域名字',
        ];
    }
}
