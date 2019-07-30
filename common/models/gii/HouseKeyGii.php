<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_key".
 *
 * @property string $hk_id 钥匙ID
 * @property string $house_id 房源编号uuid
 * @property int $hk_status 钥匙状态（1=正常 2=借出 3=退换  4=封存）
 * @property int $hk_dian 钥匙店ID
 * @property string $hk_num 钥匙编号
 * @property string $hk_shouju 收据
 * @property string $hk_deyaoshiren 得钥匙人
 * @property string $hk_jiechuyaoshiren 借出人
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 * @property string $company_id 公司ID
 */
class HouseKeyGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_key';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['house_id'], 'required'],
            [['hk_status', 'hk_dian', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['house_id'], 'string', 'max' => 50],
            [['hk_num', 'hk_shouju', 'hk_deyaoshiren', 'hk_jiechuyaoshiren'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hk_id' => '钥匙ID',
            'house_id' => '房源编号uuid',
            'hk_status' => '钥匙状态（1=正常 2=借出 3=退换  4=封存）',
            'hk_dian' => '钥匙店ID',
            'hk_num' => '钥匙编号',
            'hk_shouju' => '收据',
            'hk_deyaoshiren' => '得钥匙人',
            'hk_jiechuyaoshiren' => '借出人',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
            'company_id' => '公司ID',
        ];
    }
}
