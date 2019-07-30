<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_img".
 *
 * @property string $hi_id 字段ID
 * @property int $house_id
 * @property string $hi_url 封面
 * @property int $hi_type 1=封面 2=楼栋号 3=房号 4=主卧
 * @property int $hi_is_cover 是否封面
 * @property int $hi_status 是否审核
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除 0=没有删除  1=删除
 * @property string $company_id 公司ID
 */
class HouseImgGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_img';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['house_id'], 'required'],
            [['house_id', 'hi_type', 'hi_is_cover', 'hi_status', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['hi_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hi_id' => '字段ID',
            'house_id' => 'House ID',
            'hi_url' => '封面',
            'hi_type' => '1=封面 2=楼栋号 3=房号 4=主卧',
            'hi_is_cover' => '是否封面',
            'hi_status' => '是否审核',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除 0=没有删除  1=删除',
            'company_id' => '公司ID',
        ];
    }
}
