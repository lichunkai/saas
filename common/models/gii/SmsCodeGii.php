<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "sms_code".
 *
 * @property string $msg_id 验证码ID
 * @property string $msg_mobile 手机号码
 * @property string $msg_code 验证码
 * @property string $msg_content 短信内容
 * @property int $status 是否已用0未用1已用
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del
 */
class SmsCodeGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['msg_mobile'], 'string', 'max' => 11],
            [['msg_code'], 'string', 'max' => 6],
            [['msg_content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'msg_id' => '验证码ID',
            'msg_mobile' => '手机号码',
            'msg_code' => '验证码',
            'msg_content' => '短信内容',
            'status' => '是否已用0未用1已用',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => 'Is Del',
        ];
    }
}
