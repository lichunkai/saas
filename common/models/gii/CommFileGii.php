<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "comm_file".
 *
 * @property string $file_id 文件ID
 * @property string $file_path 文件地址
 * @property int $status 0表示不存在相关联的类型ID，1表示存在
 * @property string $type 类型：member, shop, customer
 * @property int $type_id 类型所属ID
 * @property string $ctime
 */
class CommFileGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comm_file';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('web_db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'type_id'], 'integer'],
            [['ctime'], 'safe'],
            [['file_path'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_id' => '文件ID',
            'file_path' => '文件地址',
            'status' => '0表示不存在相关联的类型ID，1表示存在',
            'type' => '类型：member, shop, customer',
            'type_id' => '类型所属ID',
            'ctime' => 'Ctime',
        ];
    }
}
