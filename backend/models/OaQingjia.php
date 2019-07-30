<?php

namespace backend\models;

use common\models\gii\OaQingjiaGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OaQingjia extends OaQingjiaGii
{

    /*
     * 普通配置配置
     */
    public function UpdateQj($param,$user){
        if(isset($param['oa_qingjia_id'])){
            $model = static::findOne($param['oa_qingjia_id']);
            $model->u_id = $user['u_id'];
        }else{
            $model = new OaQingjia();
            $model->c_id = $user['u_id'];
            $model->ctime = date('Y-m-d H:i:s');
            $model->staff_id = $user['u_id'];
            $model->d_id = $user['u_dept_id'];
            $model->status = 0;
            $model->shenqing_date = date('Y-m-d');
            $model->is_del = 0;
        }
        $model->type = $param['type'];
        $model->st_time = $param['st'].' '.$param['st_time'];
        $model->ed_time = $param['ed'].' '.$param['ed_time'];
        $model->utime = date('Y-m-d H:i:s');
        $model->remark = $param['remark'];
        if($model->save()){
            return true;
        }else{
            return false;
        }
    }
}
