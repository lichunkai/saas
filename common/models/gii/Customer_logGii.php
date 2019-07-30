<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_customer_log".
 *
 * @property string $cl_id
 * @property int $cl_type //写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加下定)7(添加带看)8(删除带看)9（添加跟进）10（查看跟进列表）11（修改状态）12（申请封盘）'
 * @property string $cl_content
 * @property string $customer_uuid 客户uuid
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property string $company_id 公司ID
 */
class Customer_logGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_customer_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cl_type', 'c_id', 'u_id', 'company_id'], 'integer'],
            [['cl_content'], 'string'],
            [['customer_uuid'], 'required'],
            [['ctime', 'utime'], 'safe'],
            [['customer_uuid'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cl_id' => 'Cl ID',
            'cl_type' => '//写日志 1(查看客源详情) 2(添加客源手机号码) 3(修改客源信息) 4(添加客源信息) 5(删除客源)6(添加下定)7(添加带看)8(删除带看)9（添加跟进）10（查看跟进列表）11（修改状态）12（申请封盘）\'',
            'cl_content' => 'Cl Content',
            'customer_uuid' => '客户uuid',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'company_id' => '公司ID',
        ];
    }
}
