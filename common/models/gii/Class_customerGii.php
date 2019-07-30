<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_class_customer".
 *
 * @property int $cc_id 字段ID
 * @property string $cc_name 客源等级名
 * @property int $cc_type 类型(0求购,1求租)
 * @property int $cc_private_return 私回访周期(全部员工)
 * @property int $cc_private_creturn 私回访周期(维护员工)
 * @property int $cc_public_return 公回访周期(全部员工)
 * @property int $cc_public_creturn 公回访周期(维护员工)
 * @property int $cc_private_look 私带看周期(全部员工)
 * @property int $cc_private_clook 私带看周期(维护员工)
 * @property int $cc_public_look 公盘带看周期(全部员工)
 * @property int $cc_public_clook 公盘带看周期(维护员工)
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $uid 用户ID
 * @property int $rid 组ID
 * @property int $sid 店ID
 * @property int $aid 区ID
 * @property int $baid 大区ID
 * @property int $cid 公司ID
 * @property int $is_del 是否删除 0=没有删除  1=删除
 * @property string $company_id 公司ID
 */
class Class_customerGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_class_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cc_type', 'cc_private_return', 'cc_private_creturn', 'cc_public_return', 'cc_public_creturn', 'cc_private_look', 'cc_private_clook', 'cc_public_look', 'cc_public_clook', 'c_id', 'u_id', 'uid', 'rid', 'sid', 'aid', 'baid', 'cid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['cc_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cc_id' => '字段ID',
            'cc_name' => '客源等级名',
            'cc_type' => '类型(0求购,1求租)',
            'cc_private_return' => '私回访周期(全部员工)',
            'cc_private_creturn' => '私回访周期(维护员工)',
            'cc_public_return' => '公回访周期(全部员工)',
            'cc_public_creturn' => '公回访周期(维护员工)',
            'cc_private_look' => '私带看周期(全部员工)',
            'cc_private_clook' => '私带看周期(维护员工)',
            'cc_public_look' => '公盘带看周期(全部员工)',
            'cc_public_clook' => '公盘带看周期(维护员工)',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'uid' => '用户ID',
            'rid' => '组ID',
            'sid' => '店ID',
            'aid' => '区ID',
            'baid' => '大区ID',
            'cid' => '公司ID',
            'is_del' => '是否删除 0=没有删除  1=删除',
            'company_id' => '公司ID',
        ];
    }
}
