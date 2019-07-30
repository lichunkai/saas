<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_school".
 *
 * @property int $s_id 字段ID
 * @property string $s_name 学区名
 * @property string $s_address 学区地址
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $uid 用户ID
 * @property int $rid 组ID
 * @property int $sid 店ID
 * @property int $aid 区ID
 * @property int $baid 大区ID
 * @property int $cid 公司ID
 * @property int $is_del 是否删除 0=没有删除  1=删除
 * @property string $company_id 公司ID
 */
class SchoolGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_school';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_id', 'u_id', 'uid', 'rid', 'sid', 'aid', 'baid', 'cid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['s_name'], 'string', 'max' => 150],
            [['s_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            's_id' => '字段ID',
            's_name' => '学区名',
            's_address' => '学区地址',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'uid' => '用户ID',
            'rid' => '组ID',
            'sid' => '店ID',
            'aid' => '区ID',
            'baid' => '大区ID',
            'cid' => '公司ID',
            'is_del' => '是否删除 0=没有删除  1=删除',
            'company_id' => '公司ID',
        ];
    }
}
