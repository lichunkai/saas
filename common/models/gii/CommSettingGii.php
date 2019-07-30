<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_comm_setting".
 *
 * @property string $id 设置ID
 * @property int $company_id 公司ID
 * @property int $type 配置类型
 * @property string $value 设置值
 * @property int $cid 创建人
 * @property int $uid 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 */
class CommSettingGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_comm_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'type', 'cid', 'uid'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '设置ID',
            'company_id' => '公司ID',
            'type' => '配置类型',
            'value' => '设置值',
            'cid' => '创建人',
            'uid' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
        ];
    }
}
