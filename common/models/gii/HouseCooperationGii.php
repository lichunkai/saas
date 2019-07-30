<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_cooperation".
 *
 * @property string $cooperation_id 合作id
 * @property string $house_id 房源uuid
 * @property int $house_type 房源类型1出租2买卖3高端
 * @property int $company_id 公司id
 * @property int $c_id 创建人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除
 */
class HouseCooperationGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_cooperation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['house_type', 'company_id', 'c_id', 'u_id', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['house_id'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cooperation_id' => '合作id',
            'house_id' => '房源uuid',
            'house_type' => '房源类型1出租2买卖3高端',
            'company_id' => '公司id',
            'c_id' => '创建人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除',
        ];
    }
}
