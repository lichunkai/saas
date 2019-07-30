<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_jiu".
 *
 * @property string $hjy_id 九要素id
 * @property int $house_id 房源ID
 * @property int $is_zheng 两证是否齐全
 * @property string $zheng_type 房源类型
 * @property string $zheng_chanquanren 产权人
 * @property string $zheng_mark 证-备注
 * @property int $is_xuequ 是否为学区
 * @property int $is_xuequ_shiyong 名额是否用过
 * @property string $xuequ_mark 学区备注
 * @property string $is_jiao 款清交房需要返租
 * @property string $jiao_date 交房时间
 * @property string $jiao_mark 交房备注
 * @property int $is_ying 贷款没有结清
 * @property string $ying_jine 金额
 * @property int $ying_huanqing 能否自己还清
 * @property int $ying_dianzi 能否垫资
 * @property string $ying_mark 银-备注
 * @property string $kan_kanfangfangshi 看房方式
 * @property string $kan_zhuangtai 房屋状态
 * @property string $kan_mark 看房备注
 * @property int $is_shui 是否满两年
 * @property string $shui_chengdan 税费承担
 * @property string $shui_mark 税费备注
 * @property int $is_mai 卖后是否再买
 * @property string $mai_mark 再买备注
 * @property int $is_hukou 是否迁走户口
 * @property string $hukou_mark 户口备注
 * @property string $peitao 配套
 * @property string $peitao_mark 配套备注
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property int $is_del
 * @property string $company_id 公司ID
 */
class HouseJiuGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_jiu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['house_id'], 'required'],
            [['house_id', 'is_zheng', 'is_xuequ', 'is_xuequ_shiyong', 'is_ying', 'ying_huanqing', 'ying_dianzi', 'is_shui', 'is_mai', 'is_hukou', 'c_id', 'u_id', 'is_del', 'company_id'], 'integer'],
            [['jiao_date', 'ctime', 'utime'], 'safe'],
            [['ying_jine'], 'number'],
            [['peitao'], 'string'],
            [['zheng_type', 'zheng_chanquanren', 'zheng_mark', 'xuequ_mark', 'is_jiao', 'jiao_mark', 'ying_mark', 'kan_kanfangfangshi', 'kan_zhuangtai', 'kan_mark', 'shui_chengdan', 'shui_mark', 'mai_mark', 'hukou_mark', 'peitao_mark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hjy_id' => '九要素id',
            'house_id' => '房源ID',
            'is_zheng' => '两证是否齐全',
            'zheng_type' => '房源类型',
            'zheng_chanquanren' => '产权人',
            'zheng_mark' => '证-备注',
            'is_xuequ' => '是否为学区',
            'is_xuequ_shiyong' => '名额是否用过',
            'xuequ_mark' => '学区备注',
            'is_jiao' => '款清交房需要返租',
            'jiao_date' => '交房时间',
            'jiao_mark' => '交房备注',
            'is_ying' => '贷款没有结清',
            'ying_jine' => '金额',
            'ying_huanqing' => '能否自己还清',
            'ying_dianzi' => '能否垫资',
            'ying_mark' => '银-备注',
            'kan_kanfangfangshi' => '看房方式',
            'kan_zhuangtai' => '房屋状态',
            'kan_mark' => '看房备注',
            'is_shui' => '是否满两年',
            'shui_chengdan' => '税费承担',
            'shui_mark' => '税费备注',
            'is_mai' => '卖后是否再买',
            'mai_mark' => '再买备注',
            'is_hukou' => '是否迁走户口',
            'hukou_mark' => '户口备注',
            'peitao' => '配套',
            'peitao_mark' => '配套备注',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'is_del' => 'Is Del',
            'company_id' => '公司ID',
        ];
    }
}
