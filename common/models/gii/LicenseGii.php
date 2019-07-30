<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_license".
 *
 * @property string $id 机器ID
 * @property string $ip 申请机器码的ID
 * @property string $code 机器码
 * @property int $is_pass 是否通过
 * @property string $mendian 门店
 * @property string $shenqingren 申请人
 * @property string $remake 备注
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 */
class LicenseGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_license';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_pass', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['ip', 'code', 'mendian', 'shenqingren', 'remake'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '机器ID',
            'ip' => '申请机器码的ID',
            'code' => '机器码',
            'is_pass' => '是否通过',
            'mendian' => '门店',
            'shenqingren' => '申请人',
            'remake' => '备注',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
        ];
    }
}
