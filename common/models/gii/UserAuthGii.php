<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_user_auth".
 *
 * @property string $ua_id ID
 * @property int $u_id 角色id
 * @property int $p_id 权限id
 * @property int $data_range 查看数据权限范围  1本人 2本组 3本店 4本区 5本大区 6所有
 * @property int $cid 创建人
 * @property string $ctime 添加时间
 * @property string $company_id 公司ID
 */
class UserAuthGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_user_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['u_id', 'p_id', 'data_range', 'cid', 'company_id'], 'integer'],
            [['ctime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ua_id' => 'ID',
            'u_id' => '角色id',
            'p_id' => '权限id',
            'data_range' => '查看数据权限范围  1本人 2本组 3本店 4本区 5本大区 6所有',
            'cid' => '创建人',
            'ctime' => '添加时间',
            'company_id' => '公司ID',
        ];
    }
}
