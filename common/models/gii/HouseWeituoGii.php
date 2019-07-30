<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_weituo".
 *
 * @property string $hw_id 委托ID
 * @property string $house_id 房源uuid
 * @property string $house_sn 房源sn
 * @property int $weituo_type 委托类型1一般2独家
 * @property string $weituo_image 委托图片
 * @property int $hw_status 委托状态（1=正常 9=失效）
 * @property int $hw_d_id 委托部门
 * @property int $hw_u_id 委托人
 * @property string $hw_sn 委托编号
 * @property string $hw_start_time 开始时间
 * @property string $hw_end_time 结束时间
 * @property int $hw_invalid_did 失效人部门
 * @property string $hw_invalid_dname 失效用户所属部门
 * @property int $hw_invalid_uid 失效人id
 * @property string $hw_invalid_uname 失效用户名
 * @property string $hw_invalid_reason 失效原因
 * @property string $hw_invalid_remark 失效备注
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $is_del 是否删除
 * @property string $company_id 公司ID
 */
class HouseWeituoGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_weituo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['house_id'], 'required'],
            [['weituo_type', 'hw_status', 'hw_d_id', 'hw_u_id', 'hw_invalid_did', 'hw_invalid_uid', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['hw_start_time', 'hw_end_time', 'ctime', 'utime'], 'safe'],
            [['house_id', 'hw_invalid_reason', 'hw_invalid_remark'], 'string', 'max' => 50],
            [['house_sn', 'weituo_image', 'hw_sn'], 'string', 'max' => 255],
            [['hw_invalid_dname', 'hw_invalid_uname'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hw_id' => '委托ID',
            'house_id' => '房源uuid',
            'house_sn' => '房源sn',
            'weituo_type' => '委托类型1一般2独家',
            'weituo_image' => '委托图片',
            'hw_status' => '委托状态（1=正常 9=失效）',
            'hw_d_id' => '委托部门',
            'hw_u_id' => '委托人',
            'hw_sn' => '委托编号',
            'hw_start_time' => '开始时间',
            'hw_end_time' => '结束时间',
            'hw_invalid_did' => '失效人部门',
            'hw_invalid_dname' => '失效用户所属部门',
            'hw_invalid_uid' => '失效人id',
            'hw_invalid_uname' => '失效用户名',
            'hw_invalid_reason' => '失效原因',
            'hw_invalid_remark' => '失效备注',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => '是否删除',
            'company_id' => '公司ID',
        ];
    }
}
