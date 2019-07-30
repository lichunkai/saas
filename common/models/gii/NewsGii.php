<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_news".
 *
 * @property string $news_id 自增id
 * @property string $news_title 标题
 * @property string $news_images 图片
 * @property string $news_content 正文
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
class NewsGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_content'], 'required'],
            [['news_content'], 'string'],
            [['is_top', 'is_pop', 'sort', 'cid', 'uid', 'is_del', 'company_id'], 'integer'],
            [['ctime', 'utime'], 'safe'],
            [['news_title'], 'string', 'max' => 100],
            [['news_images'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'news_id' => '自增id',
            'news_title' => '标题',
            'news_images' => '图片',
            'news_content' => '正文',
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
