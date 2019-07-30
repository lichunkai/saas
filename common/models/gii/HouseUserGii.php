<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_user".
 *
 * @property string $id
 * @property string $house_id 房源id
 * @property int $type 人员类型1录入人2维护人3图片人4钥匙人5一般委托6独家委托7成交人
 * @property int $user_id 员工id
 * @property int $depart_id 部门id
 * @property int $company_id 公司id
 * @property int $c_id 创建人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除
 */
class HouseUserGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'depart_id', 'company_id', 'c_id', 'u_id', 'is_del'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['house_id'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'house_id' => '房源id',
            'type' => '人员类型1录入人2维护人3图片人4钥匙人5一般委托6独家委托7成交人',
            'user_id' => '员工id',
            'depart_id' => '部门id',
            'company_id' => '公司id',
            'c_id' => '创建人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除',
        ];
    }
}
