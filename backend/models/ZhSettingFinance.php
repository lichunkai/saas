<?php

namespace backend\models;

use common\models\gii\ZhSettingFinanceGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ZhSettingFinance extends ZhSettingFinanceGii
{

    /*
     * 分成配置添加
     */
    public function addFinance($param)
    {
        $model = new ZhSettingFinance();
        $model->finance_shorthand = isset($param['finance_shorthand']) ? $param['finance_shorthand'] : '';
        $model->finance_name = isset($param['finance_name']) ? $param['finance_name'] : '';
        $model->company_id = isset($param['company_id']) ? $param['company_id'] : '';
        $model->ctime = date('Y-m-d H:i:s');
        $model->utime = date('Y-m-d H:i:s');
        $result = $model->save();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

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

    /*
     * 删除配置
     */
    public function DelSetting($param)
    {
        $model = static::findOne($param['finance_id']);
        $model->delete();
        return true;
    }
}
