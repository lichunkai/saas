<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_caiji_log".
 *
 * @property string $ch_id
 * @property int $ch_type 采集房源类型，1租房，2出售
 * @property int $ch_sign 1表示查看电话，2表示导入房源
 * @property string $ch_content 内容
 * @property int $house_id 房源ID
 * @property int $c_id 查看人
 * @property int $d_id 查看店
 * @property string $ctime 添加时间
 * @property int $company_id 公司ID
 */
class HouseCaijiLogGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_caiji_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ch_type', 'ch_sign', 'house_id', 'c_id', 'd_id', 'company_id'], 'integer'],
            [['ctime'], 'safe'],
            [['ch_content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ch_id' => 'Ch ID',
            'ch_type' => '采集房源类型，1租房，2出售',
            'ch_sign' => '1表示查看电话，2表示导入房源',
            'ch_content' => '内容',
            'house_id' => '房源ID',
            'c_id' => '查看人',
            'd_id' => '查看店',
            'ctime' => '添加时间',
            'company_id' => '公司ID',
        ];
    }
}
