<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_customer_follow".
 *
 * @property int $f_id 字段ID
 * @property string $genjinfangshi 跟进方式
 * @property string $genjinneirong 跟进内容
 * @property int $tixing_uid 提醒uid
 * @property int $bumen_id 部门id
 * @property string $tixing_time 提醒时间
 * @property string $customer_uuid 客户id
 * @property string $tixingneirong 提醒内容
 * @property int $dianhuachakan 电话查看0未查看  1查看
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
class Customer_followGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_customer_follow';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tixing_uid', 'bumen_id', 'dianhuachakan', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del', 'company_id'], 'integer'],
            [['tixing_time', 'ctime', 'utime'], 'safe'],
            [['tixingneirong'], 'string'],
            [['genjinfangshi', 'genjinneirong'], 'string', 'max' => 100],
            [['customer_uuid'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'f_id' => '字段ID',
            'genjinfangshi' => '跟进方式',
            'genjinneirong' => '跟进内容',
            'tixing_uid' => '提醒uid',
            'bumen_id' => '部门id',
            'tixing_time' => '提醒时间',
            'customer_uuid' => '客户id',
            'tixingneirong' => '提醒内容',
            'dianhuachakan' => '电话查看0未查看  1查看',
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
