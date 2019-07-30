<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_customer_xiading".
 *
 * @property int $x_id 字段ID
 * @property double $xiadingjine 下定金额
 * @property string $xiadingriqi 下定日期
 * @property string $chengjiaobianhao 成交编号
 * @property int $bumen 部门id
 * @property string $beizhu 备注
 * @property int $jingbanren_id 经办人
 * @property string $piaojuhao 票据号
 * @property string $pay_way 支付方式
 * @property double $yujijine 预计金额
 * @property string $yujichengjiao 预计成交日期
 * @property string $image 费用凭证图片
 * @property string $customer_uuid
 * @property int $house_id
 * @property int $x_zhuangtai 状态  0 待确认，1 支出，2确认，3退定，4已驳回
 * @property string $reject_reason 反驳原因
 * @property string $x_xys 协议书
 * @property string $x_zcbz 支出备注
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
class Customer_xiadingGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_customer_xiading';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['xiadingjine', 'yujijine'], 'number'],
            [['xiadingriqi', 'yujichengjiao', 'ctime', 'utime'], 'safe'],
            [['bumen', 'jingbanren_id', 'house_id', 'x_zhuangtai', 'c_id', 'u_id', 'auth_uid', 'auth_rid', 'auth_sid', 'auth_aid', 'auth_baid', 'auth_cid', 'is_del', 'company_id'], 'integer'],
            [['image'], 'string'],
            [['chengjiaobianhao', 'x_xys', 'x_zcbz'], 'string', 'max' => 100],
            [['beizhu', 'piaojuhao', 'reject_reason'], 'string', 'max' => 255],
            [['pay_way'], 'string', 'max' => 30],
            [['customer_uuid'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'x_id' => '字段ID',
            'xiadingjine' => '下定金额',
            'xiadingriqi' => '下定日期',
            'chengjiaobianhao' => '成交编号',
            'bumen' => '部门id',
            'beizhu' => '备注',
            'jingbanren_id' => '经办人',
            'piaojuhao' => '票据号',
            'pay_way' => '支付方式',
            'yujijine' => '预计金额',
            'yujichengjiao' => '预计成交日期',
            'image' => '费用凭证图片',
            'customer_uuid' => 'Customer Uuid',
            'house_id' => 'House ID',
            'x_zhuangtai' => '状态  0 待确认，1 支出，2确认，3退定，4已驳回',
            'reject_reason' => '反驳原因',
            'x_xys' => '协议书',
            'x_zcbz' => '支出备注',
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
