<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "zh_house_log".
 *
 * @property string $hl_id
 * @property int $hl_type 日志类型（1=修改信息 2=变更状态 3=上传照片 4=公盘转私盘 5=私盘转公盘 6=设为主推 7=写跟进  8=写提醒 9=封盘  10=举报  11=修改价格 12=添加电话  13=查看电话  14=查看底价 15=修改业主 16=分享房源 17=分享合作）
 * @property string $hl_content
 * @property string $house_id 房源uuid
 * @property int $c_id
 * @property int $u_id
 * @property string $ctime
 * @property string $utime
 * @property string $company_id 公司ID
 */
class HouseLogGii extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zh_house_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hl_type', 'c_id', 'u_id', 'company_id'], 'integer'],
            [['hl_content'], 'string'],
            [['house_id'], 'required'],
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
            'hl_id' => 'Hl ID',
            'hl_type' => '日志类型（1=修改信息 2=变更状态 3=上传照片 4=公盘转私盘 5=私盘转公盘 6=设为主推 7=写跟进  8=写提醒 9=封盘  10=举报  11=修改价格 12=添加电话  13=查看电话  14=查看底价 15=修改业主 16=分享房源 17=分享合作）',
            'hl_content' => 'Hl Content',
            'house_id' => '房源uuid',
            'c_id' => 'C ID',
            'u_id' => 'U ID',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
            'company_id' => '公司ID',
        ];
    }
}
