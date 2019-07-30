<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "oa_hr_biandong".
 *
 * @property int $hr_bd_id 变动ID
 * @property int $staff_id 员工ID
 * @property string $biandongleixing 变动类型
 * @property int $yx_role_id 原职务
 * @property int $yx_d_id 原部门
 * @property int $bd_role_id 变动职务(角色)
 * @property int $bd_d_id 变动部门
 * @property string $bd_date 变动日期
 * @property string $biandongyuanyin 变动原因
 * @property int $c_id 添加人
 * @property string $ctime 添加时间
 * @property int $is_del 0表示不删除，1表示删除
 * @property int $company_id 公司ID
 */
class OaHrBiandongGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_hr_biandong';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_id', 'yx_role_id', 'yx_d_id', 'bd_role_id', 'bd_d_id', 'c_id', 'is_del', 'company_id'], 'integer'],
            [['bd_date', 'ctime'], 'safe'],
            [['biandongleixing'], 'string', 'max' => 50],
            [['biandongyuanyin'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hr_bd_id' => '变动ID',
            'staff_id' => '员工ID',
            'biandongleixing' => '变动类型',
            'yx_role_id' => '原职务',
            'yx_d_id' => '原部门',
            'bd_role_id' => '变动职务(角色)',
            'bd_d_id' => '变动部门',
            'bd_date' => '变动日期',
            'biandongyuanyin' => '变动原因',
            'c_id' => '添加人',
            'ctime' => '添加时间',
            'is_del' => '0表示不删除，1表示删除',
            'company_id' => '公司ID',
        ];
    }
}
