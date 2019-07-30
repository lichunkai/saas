<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_button".
 *
 * @property string $b_id 按钮id
 * @property string $b_name 按钮名称(相当于元素id)
 * @property string $b_desc 按钮描述(相当于文本)
 * @property string $b_html 按钮所属页面（控制器）
 * @property string $b_url 按钮对应的请求
 * @property int $b_type 按钮位置1是顶部2是列表
 * @property string $b_class 按钮类型，值为primary、ghost、dashed、text、info、success、warning、error
 * @property int $cid 创建人
 * @property int $uid 更新人
 * @property string $ctime 创建时间
 * @property string $utime 更新时间
 * @property int $is_del 是否删除（0=否  1=是）
 * @property string $company_id 公司ID
 */
class ButtonGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_button';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['b_type', 'cid', 'uid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['b_name', 'b_desc', 'b_html', 'b_class'], 'string', 'max' => 50],
            [['b_url'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'b_id' => '按钮id',
            'b_name' => '按钮名称(相当于元素id)',
            'b_desc' => '按钮描述(相当于文本)',
            'b_html' => '按钮所属页面（控制器）',
            'b_url' => '按钮对应的请求',
            'b_type' => '按钮位置1是顶部2是列表',
            'b_class' => '按钮类型，值为primary、ghost、dashed、text、info、success、warning、error',
            'cid' => '创建人',
            'uid' => '更新人',
            'ctime' => '创建时间',
            'utime' => '更新时间',
            'is_del' => '是否删除（0=否  1=是）',
            'company_id' => '公司ID',
        ];
    }
}
