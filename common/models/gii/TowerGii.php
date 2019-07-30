<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_tower".
 *
 * @property int $t_id 房号id
 * @property int $bu_id 楼栋id
 * @property int $el_id 单元id
 * @property int $h_id 项目id
 * @property string $bu_floor 楼层id
 * @property string $bu_h_number 房号
 * @property double $bu_acreage 面积
 * @property double $bu_total 总价
 * @property int $sort 排序
 * @property int $bu_market 销控（0销控，1可售，2认购，3签约）
 * @property int $is_del 是否删除（0=否  1=是）
 * @property int $cid
 * @property int $uid
 * @property string $ctime
 * @property string $utime
 * @property string $company_id 公司ID
 */
class TowerGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_tower';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bu_id', 'el_id', 'h_id', 'sort', 'bu_market', 'is_del', 'cid', 'uid', 'company_id'], 'integer'],
            [['bu_acreage', 'bu_total'], 'number'],
            [['ctime', 'utime'], 'safe'],
            [['bu_floor', 'bu_h_number'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            't_id' => '房号id',
            'bu_id' => '楼栋id',
            'el_id' => '单元id',
            'h_id' => '项目id',
            'bu_floor' => '楼层id',
            'bu_h_number' => '房号',
            'bu_acreage' => '面积',
            'bu_total' => '总价',
            'sort' => '排序',
            'bu_market' => '销控（0销控，1可售，2认购，3签约）',
            'is_del' => '是否删除（0=否  1=是）',
            'cid' => 'Cid',
            'uid' => 'Uid',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'company_id' => '公司ID',
        ];
    }
}
