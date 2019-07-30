<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_phone".
 *
 * @property string $hp_id
 * @property string $house_id 房源uuid
 * @property string $hp_phone
 * @property string $hp_customer_type
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 * @property string $company_id 公司ID
 */
class HousePhoneGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_phone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['house_id'], 'required'],
            [['c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['house_id'], 'string', 'max' => 50],
            [['hp_phone', 'hp_customer_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hp_id' => 'Hp ID',
            'house_id' => '房源uuid',
            'hp_phone' => 'Hp Phone',
            'hp_customer_type' => 'Hp Customer Type',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
            'company_id' => '公司ID',
        ];
    }
}
