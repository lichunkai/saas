<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_order_sell_followup".
 *
 * @property string $of_id 自增id
 * @property int $order_id 订单id
 * @property string $of_type 跟进方式
 * @property string $of_content 跟进内容
 * @property int $d_id 部门ID
 * @property int $of_notify_user 通知人
 * @property string $of_notify_time 通知时间
 * @property string $of_notify_content 通知内容
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 添加时间
 * @property string $utime 更新时间
 * @property int $auth_uid 用户ID
 * @property int $auth_rid 组ID
 * @property int $auth_sid 店ID
 * @property int $auth_aid 区ID
 * @property int $auth_baid 大区ID
 * @property int $is_del 是否删除
 * @property string $company_id 公司ID
 */
class OrderSellFollowupGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_order_sell_followup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'd_id', 'of_notify_user', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'is_del', 'company_id'], 'integer'],
            [['of_notify_time', 'ctime', 'utime'], 'safe'],
            [['of_notify_content'], 'string'],
            [['of_type'], 'string', 'max' => 50],
            [['of_content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'of_id' => '自增id',
            'order_id' => '订单id',
            'of_type' => '跟进方式',
            'of_content' => '跟进内容',
            'd_id' => '部门ID',
            'of_notify_user' => '通知人',
            'of_notify_time' => '通知时间',
            'of_notify_content' => '通知内容',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '添加时间',
            'utime' => '更新时间',
            'auth_uid' => '用户ID',
            'auth_rid' => '组ID',
            'auth_sid' => '店ID',
            'auth_aid' => '区ID',
            'auth_baid' => '大区ID',
            'is_del' => '是否删除',
            'company_id' => '公司ID',
        ];
    }
}
