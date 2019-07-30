<?php

namespace backend\models;

use common\models\gii\OaKaoqingSettingGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OaKaoqingSetting extends OaKaoqingSettingGii
{

    /*
     * 普通配置配置
     */
    public function UpdateSetting($param,$user){
        if(isset($param['kq_st_id']) && $param['kq_st_id']) {
            $model = static::findOne($param['kq_st_id']);
            $model->u_id = $user['u_id'];
        }else{
            $model =new OaKaoqingSetting();
            $model->c_id = $user['u_id'];
            $model->ctime = date('Y-m-d H:i:s');
        }
        $model->kq_tp_id = $param['kq_tp_id'];
        $model->d_id = $param['d_id'];
        $model->utime = date('Y-m-d H:i:s');
        if($model->save()){
            return true;
        }else{
            return false;
        }
    }
}
