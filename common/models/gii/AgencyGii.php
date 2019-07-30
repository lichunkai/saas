<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_agency".
 *
 * @property string $ag_id ID
 * @property string $ag_name 分销公司名
 * @property string $ag_legalperson 分销公司法人
 * @property int $ag_province 省
 * @property string $ag_province_name 省份名
 * @property int $ag_city 市
 * @property string $ag_city_name 城市名
 * @property int $ag_area 区
 * @property string $ag_area_name 区域名
 * @property string $ag_adress 分销公司地址
 * @property string $ag_img_path 图片路径
 * @property int $ag_size 机构规模
 * @property string $ag_nature 公司性质
 * @property string $ag_leader 分销机构负责人
 * @property string $ag_mobile 分销公司联系方式
 * @property string $ag_leader_post 负责人职务
 * @property string $ag_remark 备注
 * @property int $cid 创建人
 * @property int $uid 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除（0=否  1=是）
 */
class AgencyGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_agency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ag_province', 'ag_city', 'ag_area', 'ag_size', 'cid', 'uid', 'is_del'], 'integer'],
            [['ag_img_path', 'ag_remark'], 'string'],
            [['ctime', 'utime'], 'safe'],
            [['ag_name', 'ag_adress'], 'string', 'max' => 100],
            [['ag_legalperson'], 'string', 'max' => 50],
            [['ag_province_name', 'ag_city_name', 'ag_area_name', 'ag_mobile', 'ag_leader_post'], 'string', 'max' => 20],
            [['ag_nature', 'ag_leader'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ag_id' => 'ID',
            'ag_name' => '分销公司名',
            'ag_legalperson' => '分销公司法人',
            'ag_province' => '省',
            'ag_province_name' => '省份名',
            'ag_city' => '市',
            'ag_city_name' => '城市名',
            'ag_area' => '区',
            'ag_area_name' => '区域名',
            'ag_adress' => '分销公司地址',
            'ag_img_path' => '图片路径',
            'ag_size' => '机构规模',
            'ag_nature' => '公司性质',
            'ag_leader' => '分销机构负责人',
            'ag_mobile' => '分销公司联系方式',
            'ag_leader_post' => '负责人职务',
            'ag_remark' => '备注',
            'cid' => '创建人',
            'uid' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除（0=否  1=是）',
        ];
    }
}
