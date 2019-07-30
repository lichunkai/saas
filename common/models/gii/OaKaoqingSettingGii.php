<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "oa_kaoqing_setting".
 *
 * @property int $kq_st_id 考勤设置ID
 * @property int $d_id 部门ID
 * @property int $kq_tp_id 模板ID
 * @property string $latitude 经度
 * @property string $longitude 纬度
 * @property int $c_id 添加人
 * @property int $u_id 修改人
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property int $company_id 公司ID
 */
class OaKaoqingSettingGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_kaoqing_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['d_id', 'kq_tp_id', 'c_id', 'u_id', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['latitude', 'longitude'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kq_st_id' => '考勤设置ID',
            'd_id' => '部门ID',
            'kq_tp_id' => '模板ID',
            'latitude' => '经度',
            'longitude' => '纬度',
            'c_id' => '添加人',
            'u_id' => '修改人',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'company_id' => '公司ID',
        ];
    }
}
