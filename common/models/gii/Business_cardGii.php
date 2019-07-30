<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_business_card".
 *
 * @property int $bc_id
 * @property string $house_uuid
 * @property int $company_id
 * @property int $u_id 用户id
 * @property int $status 名片推荐状态  0  推荐1不推荐
 * @property string $ctime
 * @property string $utime
 */
class Business_cardGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_business_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'u_id', 'status'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['house_uuid'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bc_id' => 'Bc ID',
            'house_uuid' => 'House Uuid',
            'company_id' => 'Company ID',
            'u_id' => '用户id',
            'status' => '名片推荐状态  0  推荐1不推荐',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
        ];
    }
}
