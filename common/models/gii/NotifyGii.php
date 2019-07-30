<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_notify".
 *
 * @property string $n_id 通知ID
 * @property string $n_content 通知内容
 * @property int $n_u_id 通知人
 * @property string $n_time 通知时间
 * @property int $n_is_read 是否已读
 * @property string $n_title 标题
 * @property string $n_url url
 * @property int $n_is_notify 是否通知过
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
class NotifyGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_notify';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['n_content'], 'string'],
            [['n_u_id', 'n_is_read', 'n_is_notify', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'is_del', 'company_id'], 'integer'],
            [['n_time', 'ctime', 'utime'], 'safe'],
            [['n_title', 'n_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'n_id' => '通知ID',
            'n_content' => '通知内容',
            'n_u_id' => '通知人',
            'n_time' => '通知时间',
            'n_is_read' => '是否已读',
            'n_title' => '标题',
            'n_url' => 'url',
            'n_is_notify' => '是否通知过',
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
