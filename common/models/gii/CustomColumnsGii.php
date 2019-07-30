<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_custom_columns".
 *
 * @property string $id 自增id
 * @property int $user_id 用户id
 * @property int $module 所属模块1租赁成交2买卖成交3高端成交
 * @property string $columns 自定义字段
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 * @property string $company_id 公司ID
 */
class CustomColumnsGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_custom_columns';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'module', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['columns'], 'required'],
            [['columns'], 'string'],
            [['ctime', 'utime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'user_id' => '用户id',
            'module' => '所属模块1租赁成交2买卖成交3高端成交',
            'columns' => '自定义字段',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
            'company_id' => '公司ID',
        ];
    }
}
