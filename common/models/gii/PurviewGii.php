<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_purview".
 *
 * @property string $p_id 自增id
 * @property string $p_name 功能名称
 * @property string $p_desp 功能描述
 * @property string $p_url 功能url
 * @property int $system_type 系统类型1后台2业务
 * @property int $p_type 功能类型0选择1菜单
 * @property int $is_auth 是否显示在权限列表0不显示1显示
 * @property string $p_ico 功能图标
 * @property int $p_pid 父级功能id
 * @property int $sort 排序
 * @property int $cid 创建人
 * @property int $uid 修改人
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property int $is_del 0表示不删除，1表示删除
 * @property string $company_id 公司ID
 */
class PurviewGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_purview';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['system_type', 'p_type', 'is_auth', 'p_pid', 'sort', 'cid', 'uid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['p_name'], 'string', 'max' => 50],
            [['p_desp', 'p_url'], 'string', 'max' => 100],
            [['p_ico'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'p_id' => '自增id',
            'p_name' => '功能名称',
            'p_desp' => '功能描述',
            'p_url' => '功能url',
            'system_type' => '系统类型1后台2业务',
            'p_type' => '功能类型0选择1菜单',
            'is_auth' => '是否显示在权限列表0不显示1显示',
            'p_ico' => '功能图标',
            'p_pid' => '父级功能id',
            'sort' => '排序',
            'cid' => '创建人',
            'uid' => '修改人',
            'ctime' => '添加时间',
            'utime' => '修改时间',
            'is_del' => '0表示不删除，1表示删除',
            'company_id' => '公司ID',
        ];
    }
}
