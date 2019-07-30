<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "com_area".
 *
 * @property string $ar_id 地区ID
 * @property int $ar_p_id
 * @property int $ar_code
 * @property int $ar_sort_code
 * @property string $ar_name
 * @property string $ar_quick_query
 * @property string $ar_simple_spelling
 * @property string $ar_first_spelling
 * @property int $ar_level
 * @property string $ar_desciption
 * @property int $ar_enabled
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 * @property string $company_id 公司ID
 */
class ComAreaGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'com_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ar_p_id', 'ar_code', 'ar_sort_code', 'ar_level', 'ar_enabled', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['ar_name', 'ar_quick_query', 'ar_desciption'], 'string', 'max' => 100],
            [['ar_simple_spelling'], 'string', 'max' => 50],
            [['ar_first_spelling'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ar_id' => '地区ID',
            'ar_p_id' => 'Ar P ID',
            'ar_code' => 'Ar Code',
            'ar_sort_code' => 'Ar Sort Code',
            'ar_name' => 'Ar Name',
            'ar_quick_query' => 'Ar Quick Query',
            'ar_simple_spelling' => 'Ar Simple Spelling',
            'ar_first_spelling' => 'Ar First Spelling',
            'ar_level' => 'Ar Level',
            'ar_desciption' => 'Ar Desciption',
            'ar_enabled' => 'Ar Enabled',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
            'company_id' => '公司ID',
        ];
    }
}
