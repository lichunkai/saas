<?php

namespace backend\models;

use common\models\gii\ZhSettingTransferGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ZhSettingTransfer extends ZhSettingTransferGii
{
    /*
     * 分成配置内容添加更新
     */
    public function updateFinance($param)
    {
        $model = static::findOne($param['finance_id']);
        $model->finance_desp = isset($param['finance_desp']) ? $param['finance_desp'] : '';
        $model->utime = date('Y-m-d H:i:s');
        $result = $model->save();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}
