<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_gongzi".
 *
 * @property int $g_id 字段ID
 * @property double $jiangli 奖励
 * @property double $fakuan 罚款
 * @property string $gld_tichengfangan
 * @property string $gld_tichengfangan_json
 * @property string $gr_tichengfangan 提成方案
 * @property string $gr_tichengfangan_json 提成方案详情
 * @property string $zyd_tichengfangan
 * @property string $zyd_tichengfangan_json
 * @property int $gr_scmy_id 业绩提成id
 * @property int $gr_scm_id 方案ID
 * @property int $gld_scmy_id 管理店提成id
 * @property int $gld_scm_id 管理店提成id
 * @property int $zyd_scmy_id 直营店
 * @property int $zyd_scm_id 直营店
 * @property double $jibengongzi 基本工资
 * @property double $wuxiangeren 五险(个人)
 * @property double $wuxianyijingeren 五险一金(个人)
 * @property string $gongziriqi 工资月份用于更新 月工资 
 * @property double $gr_tichengjine 个人提成金额
 * @property double $gr_zongyeji 个人总业绩
 * @property double $gld_zongyeji 管理店总业绩
 * @property double $gld_tichengjine 管理店提成金额
 * @property double $zyd_zongyeji 直营店总业绩
 * @property double $zyd_tichengjine 直营店提成金额
 * @property string $gr_yjjs 个人业绩计算过程
 * @property string $gld_yjjs 管理店业绩计算过程
 * @property string $zyd_yjjs 直营店计算过程
 * @property string $kaishiriqi 开始日期
 * @property string $jieshuriqi 结束日期
 * @property int $user_id 工资用户id
 * @property double $hejigongzi 合计工资
 * @property int $is_payoff 是否发放（0未发放，1已发放）
 * @property int $c_id 添加人
 * @property int $u_id 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $auth_uid 用户ID
 * @property int $auth_rid 组ID
 * @property int $auth_sid 店ID
 * @property int $auth_aid 区ID
 * @property int $auth_baid 大区ID
 * @property int $auth_cid 公司ID
 * @property int $is_del 是否删除 0=没有删除  1=删除
 * @property string $company_id 公司ID
 */
class GongziGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_gongzi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jiangli', 'fakuan', 'jibengongzi', 'wuxiangeren', 'wuxianyijingeren', 'gr_tichengjine', 'gr_zongyeji', 'gld_zongyeji', 'gld_tichengjine', 'zyd_zongyeji', 'zyd_tichengjine', 'hejigongzi'], 'number'],
            [['gr_scmy_id', 'gr_scm_id', 'gld_scmy_id', 'gld_scm_id', 'zyd_scmy_id', 'zyd_scm_id', 'user_id', 'is_payoff', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del', 'company_id'], 'integer'],
            [['gr_yjjs', 'gld_yjjs', 'zyd_yjjs'], 'string'],
            [['kaishiriqi', 'jieshuriqi', 'ctime', 'utime'], 'safe'],
            [['gld_tichengfangan', 'gr_tichengfangan'], 'string', 'max' => 50],
            [['gld_tichengfangan_json', 'gr_tichengfangan_json', 'zyd_tichengfangan', 'zyd_tichengfangan_json'], 'string', 'max' => 255],
            [['gongziriqi'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'g_id' => '字段ID',
            'jiangli' => '奖励',
            'fakuan' => '罚款',
            'gld_tichengfangan' => 'Gld Tichengfangan',
            'gld_tichengfangan_json' => 'Gld Tichengfangan Json',
            'gr_tichengfangan' => '提成方案',
            'gr_tichengfangan_json' => '提成方案详情',
            'zyd_tichengfangan' => 'Zyd Tichengfangan',
            'zyd_tichengfangan_json' => 'Zyd Tichengfangan Json',
            'gr_scmy_id' => '业绩提成id',
            'gr_scm_id' => '方案ID',
            'gld_scmy_id' => '管理店提成id',
            'gld_scm_id' => '管理店提成id',
            'zyd_scmy_id' => '直营店',
            'zyd_scm_id' => '直营店',
            'jibengongzi' => '基本工资',
            'wuxiangeren' => '五险(个人)',
            'wuxianyijingeren' => '五险一金(个人)',
            'gongziriqi' => '工资月份用于更新 月工资 ',
            'gr_tichengjine' => '个人提成金额',
            'gr_zongyeji' => '个人总业绩',
            'gld_zongyeji' => '管理店总业绩',
            'gld_tichengjine' => '管理店提成金额',
            'zyd_zongyeji' => '直营店总业绩',
            'zyd_tichengjine' => '直营店提成金额',
            'gr_yjjs' => '个人业绩计算过程',
            'gld_yjjs' => '管理店业绩计算过程',
            'zyd_yjjs' => '直营店计算过程',
            'kaishiriqi' => '开始日期',
            'jieshuriqi' => '结束日期',
            'user_id' => '工资用户id',
            'hejigongzi' => '合计工资',
            'is_payoff' => '是否发放（0未发放，1已发放）',
            'c_id' => '添加人',
            'u_id' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'auth_uid' => '用户ID',
            'auth_rid' => '组ID',
            'auth_sid' => '店ID',
            'auth_aid' => '区ID',
            'auth_baid' => '大区ID',
            'auth_cid' => '公司ID',
            'is_del' => '是否删除 0=没有删除  1=删除',
            'company_id' => '公司ID',
        ];
    }
}
