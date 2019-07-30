<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_notice".
 *
 * @property string $notice_id 自增id
 * @property string $notice_type 公告类型
 * @property string $notice_title 标题
 * @property string $notice_image 图片
 * @property string $notice_content 正文
 * @property int $is_top 是否置顶1为置顶0为不置顶
 * @property int $is_pop 是否弹出
 * @property int $sort 排序
 * @property int $cid 创建人
 * @property int $uid 修改人
 * @property string $ctime 添加时间
 * @property string $utime 修改时间
 * @property int $is_del 0表示不删除，1表示删除
 * @property string $company_id 公司ID
 */
class NoticeGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_notice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notice_content'], 'required'],
            [['notice_content'], 'string'],
            [['is_top', 'is_pop', 'sort', 'cid', 'uid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['notice_type'], 'string', 'max' => 20],
            [['notice_title'], 'string', 'max' => 100],
            [['notice_image'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'notice_id' => '自增id',
            'notice_type' => '公告类型',
            'notice_title' => '标题',
            'notice_image' => '图片',
            'notice_content' => '正文',
            'is_top' => '是否置顶1为置顶0为不置顶',
            'is_pop' => '是否弹出',
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
