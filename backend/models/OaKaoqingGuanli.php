<?php

namespace backend\models;

use common\models\gii\OaKaoqingGuanliGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OaKaoqingGuanli extends OaKaoqingGuanliGii
{

    /*
     * 普通配置配置
     */
    public function UpdateKq($param,$user){
        $model = static::findOne($param['kq_mg_id']);
        $model->u_id = $user['u_id'];
        $model->sj_st = $param['sj_st'];
        $model->sj_ed = $param['sj_ed'];
        $model->flag = 9;
        $model->utime = date('Y-m-d H:i:s');
        $model->company_id = $user['company_id'];
        if($model->save()){
            return true;
        }else{
            return false;
        }
    }
}
