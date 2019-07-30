<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_class_if".
 *
 * @property int $ci_id 字段ID
 * @property int $ci_type 类型(0出售，1出租，2求购，3求租)
 * @property string $ci_class 等级
 * @property string $ci_name 方案名称
 * @property string $ci_if 条件
 * @property string $ci_property 产权性质
 * @property string $ci_fitment 装修情况
 * @property string $ci_price_min 价格区间-最小
 * @property string $ci_price_max 价格区间-最大
 * @property string $ci_area_min 面积区间-小
 * @property string $ci_area_max 面积区间-最大
 * @property string $ci_house_use 房屋用途
 * @property int $ci_daikan 大于等于
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
class Class_ifGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_class_if';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ci_type', 'ci_daikan', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['ci_class', 'ci_name', 'ci_if', 'ci_property', 'ci_fitment', 'ci_price_min', 'ci_price_max', 'ci_area_min', 'ci_area_max', 'ci_house_use'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ci_id' => '字段ID',
            'ci_type' => '类型(0出售，1出租，2求购，3求租)',
            'ci_class' => '等级',
            'ci_name' => '方案名称',
            'ci_if' => '条件',
            'ci_property' => '产权性质',
            'ci_fitment' => '装修情况',
            'ci_price_min' => '价格区间-最小',
            'ci_price_max' => '价格区间-最大',
            'ci_area_min' => '面积区间-小',
            'ci_area_max' => '面积区间-最大',
            'ci_house_use' => '房屋用途',
            'ci_daikan' => '大于等于',
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
