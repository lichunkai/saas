<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_role_auth".
 *
 * @property string $ra_id ID
 * @property int $r_id 角色id
 * @property int $p_id 权限id
 * @property int $data_range 查看数据权限
 * @property int $cid 创建人
 * @property string $ctime 添加时间
 * @property string $company_id 公司ID
 */
class RoleAuthGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_role_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['r_id', 'p_id', 'data_range', 'cid', 'company_id'], 'integer'],
            [['ctime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ra_id' => 'ID',
            'r_id' => '角色id',
            'p_id' => '权限id',
            'data_range' => '查看数据权限',
            'cid' => '创建人',
            'ctime' => '添加时间',
            'company_id' => '公司ID',
        ];
    }
}
