<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_followup".
 *
 * @property string $hf_id
 * @property string $house_id 房源id
 * @property string $hf_type 跟进方式
 * @property string $hf_content 跟进内容
 * @property int $d_id 部门ID
 * @property string $hf_depart_json 部门json
 * @property int $hf_notify_user 通知人
 * @property string $hf_notify_time 通知时间
 * @property string $hf_notify_content 通知内容
 * @property int $hf_notify_is_chedan 是否通知撤单 0=否  1=是
 * @property string $chedan 撤单原因
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
class HouseFollowupGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_followup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['d_id', 'hf_notify_user', 'hf_notify_is_chedan', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'is_del', 'company_id'], 'integer'],
            [['hf_depart_json', 'hf_notify_content'], 'string'],
            [['hf_notify_time', 'ctime', 'utime'], 'safe'],
            [['house_id'], 'string', 'max' => 50],
            [['hf_type', 'hf_content', 'chedan'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hf_id' => 'Hf ID',
            'house_id' => '房源id',
            'hf_type' => '跟进方式',
            'hf_content' => '跟进内容',
            'd_id' => '部门ID',
            'hf_depart_json' => '部门json',
            'hf_notify_user' => '通知人',
            'hf_notify_time' => '通知时间',
            'hf_notify_content' => '通知内容',
            'hf_notify_is_chedan' => '是否通知撤单 0=否  1=是',
            'chedan' => '撤单原因',
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
