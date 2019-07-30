<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "org_wechat_user".
 *
 * @property int $id
 * @property string $uuid 用户uuid
 * @property string $openid
 * @property string $nickname 微信昵称
 * @property int $sex 性别（sex 1为男）
 * @property string $headimgurl 头像
 * @property string $country 国家
 * @property string $province 省份
 * @property string $city 城市
 * @property string $member_name 员工姓名
 * @property string $member_phone 员工手机号
 * @property int $is_member 是否是员工1是2否
 * @property int $created_at
 */
class OrgWechatUserGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'org_wechat_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['openid', 'nickname'], 'required'],
            [['sex', 'is_member', 'created_at'], 'integer'],
            [['uuid', 'nickname', 'country', 'province', 'city', 'member_name'], 'string', 'max' => 50],
            [['openid', 'headimgurl'], 'string', 'max' => 255],
            [['member_phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => '用户uuid',
            'openid' => 'Openid',
            'nickname' => '微信昵称',
            'sex' => '性别（sex 1为男）',
            'headimgurl' => '头像',
            'country' => '国家',
            'province' => '省份',
            'city' => '城市',
            'member_name' => '员工姓名',
            'member_phone' => '员工手机号',
            'is_member' => '是否是员工1是2否',
            'created_at' => 'Created At',
        ];
    }
}
