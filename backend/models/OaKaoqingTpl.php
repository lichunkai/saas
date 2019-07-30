<?php

namespace backend\models;

use common\models\gii\OaKaoqingTplGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OaKaoqingTpl extends OaKaoqingTplGii
{

    /*
     * 普通配置配置
     */
    public function UpdateTpl($param,$user){
        if(isset($param['kq_tp_id']) && $param['kq_tp_id']) {
            $model = static::findOne($param['kq_tp_id']);
            $model->u_id = $user['u_id'];
        }else{
            $model =new OaKaoqingTpl();
            $model->c_id = $user['u_id'];
            $model->ctime = date('Y-m-d H:i:s');
        }
        $kq_tp_timerange = isset($param['kq_tp_timerange']) ? $param['kq_tp_timerange'] : array('', '');
        $kq_tp_days = isset($param['kq_tp_days']) ? $param['kq_tp_days'] : [];
        $weeks = ['周一', '周二', '周三', '周四', '周五', '周六', '周日'];
        $arr = [];
        foreach($weeks as $val){
            if(in_array($val, $kq_tp_days)){
                $arr[] = $val;
            }
        }
        $model->kq_tp_name = $param['kq_tp_name'];
        $model->kq_tp_days = $kq_tp_days ? join(',', $arr) : '';
        $model->kq_tp_st = $kq_tp_timerange[0];
        $model->kq_tp_ed = $kq_tp_timerange[1];
        $model->is_def = 0;
        $model->utime = date('Y-m-d H:i:s');
        $model->company_id = $user['company_id'];
        if($model->save()){
            return true;
        }else{
            return false;
        }
    }
}
