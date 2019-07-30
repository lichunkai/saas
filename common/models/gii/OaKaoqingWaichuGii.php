<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "oa_kaoqing_waichu".
 *
 * @property string $wc_id 外出ID
 * @property string $wc_time 打卡时间
 * @property string $wc_photo 图片地址
 * @property string $wc_addr 上传地址
 * @property string $lat 精度
 * @property string $lng 纬度
 * @property string $content 外出说明
 * @property int $d_id 部门ID
 * @property int $u_id 外出人ID
 * @property string $ctime 添加时间
 * @property int $is_del 0表示不删除，1表示删除
 * @property int $company_id 公司ID
 */
class OaKaoqingWaichuGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_kaoqing_waichu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wc_time', 'ctime'], 'safe'],
            [['wc_photo'], 'string'],
            [['d_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['wc_addr', 'content'], 'string', 'max' => 255],
            [['lat', 'lng'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wc_id' => '外出ID',
            'wc_time' => '打卡时间',
            'wc_photo' => '图片地址',
            'wc_addr' => '上传地址',
            'lat' => '精度',
            'lng' => '纬度',
            'content' => '外出说明',
            'd_id' => '部门ID',
            'u_id' => '外出人ID',
            'ctime' => '添加时间',
            'is_del' => '0表示不删除，1表示删除',
            'company_id' => '公司ID',
        ];
    }
}
