<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_building".
 *
 * @property int $bu_id
 * @property int $bu_ridgepole 栋
 * @property string $remark 备注
 * @property int $h_id 楼盘id
 * @property int $is_del 是否删除（0=否  1=是）
 * @property int $cid 创建人
 * @property int $uid 用户id
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 */
class BuildingGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_building';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bu_ridgepole', 'h_id', 'is_del', 'cid', 'uid'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['remark'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bu_id' => 'Bu ID',
            'bu_ridgepole' => '栋',
            'remark' => '备注',
            'h_id' => '楼盘id',
            'is_del' => '是否删除（0=否  1=是）',
            'cid' => '创建人',
            'uid' => '用户id',
            'ctime' => '创建时间',
            'utime' => '更新时间',
        ];
    }
}
