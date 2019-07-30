<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "com_community".
 *
 * @property int $cu_id 字段ID
 * @property string $cu_name 小区名称
 * @property int $biz_id 片区ID
 * @property string $biz_name 片区名称
 * @property string $cu_price 出售均价
 * @property string $cu_address 小区地址
 * @property string $cu_jctime
 * @property string $cu_wygs
 * @property string $cu_kfs
 * @property string $cu_wyf
 * @property int $cu_ldzs
 * @property int $cu_fwzs
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $cu_status 公用=0 私有=1
 * @property string $company_id 公司ID
 * @property int $is_del 是否删除 0=没有删除  1=删除
 */
class ComCommunityGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'com_community';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['biz_id', 'cu_ldzs', 'cu_fwzs', 'c_id', 'u_id', 'cu_status', 'company_id', 'is_del'], 'integer'],
            [['cu_price'], 'number'],
            [['ctime', 'utime'], 'safe'],
            [['cu_name'], 'string', 'max' => 100],
            [['biz_name', 'cu_address', 'cu_jctime', 'cu_wygs', 'cu_kfs', 'cu_wyf'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cu_id' => '字段ID',
            'cu_name' => '小区名称',
            'biz_id' => '片区ID',
            'biz_name' => '片区名称',
            'cu_price' => '出售均价',
            'cu_address' => '小区地址',
            'cu_jctime' => 'Cu Jctime',
            'cu_wygs' => 'Cu Wygs',
            'cu_kfs' => 'Cu Kfs',
            'cu_wyf' => 'Cu Wyf',
            'cu_ldzs' => 'Cu Ldzs',
            'cu_fwzs' => 'Cu Fwzs',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'cu_status' => '公用=0 私有=1',
            'company_id' => '公司ID',
            'is_del' => '是否删除 0=没有删除  1=删除',
        ];
    }
}
