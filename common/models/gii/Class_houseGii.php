<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_class_house".
 *
 * @property int $ch_id 字段ID
 * @property string $company_id 公司ID
 * @property string $ch_name 房源等级名
 * @property int $ch_type 类型(0求购,1求租)
 * @property int $ch_private_genjin 共享盘跟进周期
 * @property int $ch_private_visit 共享盘带看周期
 * @property int $ch_store_genjin 店公盘跟进周期
 * @property int $ch_store_visit 店公盘带看周期
 * @property int $ch_company_genjin 公司公盘跟进周期
 * @property int $ch_company_visit 公司公盘带看周期
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除 0=没有删除  1=删除
 */
class Class_houseGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_class_house';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'ch_type', 'ch_private_genjin', 'ch_private_visit', 'ch_store_genjin', 'ch_store_visit', 'ch_company_genjin', 'ch_company_visit', 'c_id', 'u_id', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['ch_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ch_id' => '字段ID',
            'company_id' => '公司ID',
            'ch_name' => '房源等级名',
            'ch_type' => '类型(0求购,1求租)',
            'ch_private_genjin' => '共享盘跟进周期',
            'ch_private_visit' => '共享盘带看周期',
            'ch_store_genjin' => '店公盘跟进周期',
            'ch_store_visit' => '店公盘带看周期',
            'ch_company_genjin' => '公司公盘跟进周期',
            'ch_company_visit' => '公司公盘带看周期',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除 0=没有删除  1=删除',
        ];
    }
}
