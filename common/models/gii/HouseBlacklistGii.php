<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_blacklist".
 *
 * @property int $b_id
 * @property int $cooperation_id 合作id
 * @property int $house_id
 * @property int $company_id 公司id
 * @property string $reason 屏蔽原因
 * @property int $c_id 创建人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除
 */
class HouseBlacklistGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_blacklist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cooperation_id', 'house_id', 'company_id', 'c_id', 'is_del'], 'integer'],
            [['reason'], 'string'],
            [['ctime', 'utime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'b_id' => 'B ID',
            'cooperation_id' => '合作id',
            'house_id' => 'House ID',
            'company_id' => '公司id',
            'reason' => '屏蔽原因',
            'c_id' => '创建人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除',
        ];
    }
}
