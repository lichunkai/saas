<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_customer_tel".
 *
 * @property int $tel_id 字段ID
 * @property string $tel_phone 电话
 * @property string $tel_type 电话所属
 * @property string $customer_private 公盘私盘
 * @property string $customer_uuid 客户uuid
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
class Customer_telGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_customer_tel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['tel_phone', 'tel_type'], 'string', 'max' => 100],
            [['customer_private'], 'string', 'max' => 10],
            [['customer_uuid'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tel_id' => '字段ID',
            'tel_phone' => '电话',
            'tel_type' => '电话所属',
            'customer_private' => '公盘私盘',
            'customer_uuid' => '客户uuid',
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
