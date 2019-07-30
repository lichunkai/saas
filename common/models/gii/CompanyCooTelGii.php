<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "org_company_cooperation_tel".
 *
 * @property int $occ_id 对外合作id
 * @property string $occ_name 对外合作人姓名
 * @property string $occ_tel 对外合作电话
 * @property int $company_id 公司id
 * @property int $is_del
 * @property string $ctime
 * @property string $utime
 */
class CompanyCooTelGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'org_company_cooperation_tel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['occ_name', 'occ_tel'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'occ_id' => '对外合作id',
            'occ_name' => '对外合作人姓名',
            'occ_tel' => '对外合作电话',
            'company_id' => '公司id',
            'is_del' => 'Is Del',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
        ];
    }
}
