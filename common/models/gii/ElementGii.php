<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_element".
 *
 * @property int $el_id
 * @property int $el_element 几单元
 * @property int $bu_id 所属楼栋id
 * @property int $h_id 楼盘
 * @property int $is_del 是否删除（0=否  1=是）
 * @property int $cid 创建人
 * @property int $uid 用户id
 * @property string $ctime
 * @property string $utime
 */
class ElementGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_element';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['el_element', 'bu_id', 'h_id', 'is_del', 'cid', 'uid'], 'integer'],
            [['ctime', 'utime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'el_id' => 'El ID',
            'el_element' => '几单元',
            'bu_id' => '所属楼栋id',
            'h_id' => '楼盘',
            'is_del' => '是否删除（0=否  1=是）',
            'cid' => '创建人',
            'uid' => '用户id',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
        ];
    }
}
