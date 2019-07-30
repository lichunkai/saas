<?php

namespace backend\models;

use common\models\gii\ZhSettingJueceGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ZhSettingJuece extends ZhSettingJueceGii
{

    /*
     * 普通配置配置
     */
    public function UpdateSetting($param,$user){
        if(isset($param['jsetting_id']) && $param['jsetting_id']){
            $model = static::findOne($param['jsetting_id']);
            $model->jsetting_shorthand = $param['jsetting_shorthand'];
            $model->company_id = $user['compay_id'];
            $model->jsetting_type = $param['jsetting_type'];
            $model->jsetting_name = $param['jsetting_name'];
            $model->jsetting_desp = $param['jsetting_desp'];
            $model->val_type = $param['val_type'];
            $model->val = $param['val'];
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model =new ZhSettingJuece();
            $model->jsetting_shorthand = $param['jsetting_shorthand'];
            $model->jsetting_type = $param['jsetting_type'];
            $model->jsetting_name = $param['jsetting_name'];
            $model->jsetting_desp = $param['jsetting_desp'];
            $model->val_type = $param['val_type'];
            $model->val = $param['val'];
            $model->utime = date('Y-m-d H:i:s');
            $model->ctime = date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }
    }

    /*
     * 删除配置
     */
    public function DelSetting($param){
        $model = static::findOne($param['jsetting_id']);
        $model->delete();
        return true;
    }
}
