<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_key_log".
 *
 * @property string $hkl_id 日志ID
 * @property int $hk_id 钥匙ID
 * @property string $hkl_content 日志内容
 * @property int $hkl_user 关联的人
 * @property int $c_id 创建人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除
 * @property string $company_id 公司ID
 */
class HouseKeyLogGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_key_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hk_id'], 'required'],
            [['hk_id', 'hkl_user', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['hkl_content'], 'string'],
            [['ctime', 'utime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hkl_id' => '日志ID',
            'hk_id' => '钥匙ID',
            'hkl_content' => '日志内容',
            'hkl_user' => '关联的人',
            'c_id' => '创建人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除',
            'company_id' => '公司ID',
        ];
    }
}
