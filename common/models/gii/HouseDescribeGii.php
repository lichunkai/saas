<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_describe".
 *
 * @property string $hd_id
 * @property string $house_id 合作id
 * @property string $hd_content
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 添加时间
 * @property string $utime 更新时间
 * @property int $auth_uid 用户ID
 * @property int $auth_rid 组ID
 * @property int $auth_sid 店ID
 * @property int $auth_aid 区ID
 * @property int $auth_baid 大区ID
 * @property int $is_del 是否删除
 * @property string $company_id 公司ID
 */
class HouseDescribeGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_describe';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['house_id'], 'required'],
            [['hd_content'], 'string'],
            [['c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'is_del', 'company_id'], 'integer'],
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
            'hd_id' => 'Hd ID',
            'house_id' => '合作id',
            'hd_content' => 'Hd Content',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '添加时间',
            'utime' => '更新时间',
            'auth_uid' => '用户ID',
            'auth_rid' => '组ID',
            'auth_sid' => '店ID',
            'auth_aid' => '区ID',
            'auth_baid' => '大区ID',
            'is_del' => '是否删除',
            'company_id' => '公司ID',
        ];
    }
}
