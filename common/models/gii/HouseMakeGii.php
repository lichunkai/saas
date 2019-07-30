<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_make".
 *
 * @property string $hm_id 补充信息ID
 * @property int $house_id 房源ID
 * @property string $hm_name 字段名称
 * @property string $hm_value 字段值
 * @property string $hm_type 字段类型
 * @property int $hm_sort 字段排序
 * @property string $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 * @property string $company_id 公司ID
 */
class HouseMakeGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_make';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['house_id'], 'required'],
            [['house_id', 'hm_sort', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['hm_name', 'hm_value', 'hm_type', 'c_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hm_id' => '补充信息ID',
            'house_id' => '房源ID',
            'hm_name' => '字段名称',
            'hm_value' => '字段值',
            'hm_type' => '字段类型',
            'hm_sort' => '字段排序',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
            'company_id' => '公司ID',
        ];
    }
}
