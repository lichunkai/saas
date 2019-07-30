<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\District_sliceGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class District_slice extends District_sliceGii
{
    public function updateDistrict_slice($val,$user)
    {
        if(!empty($val['dts_id'])){
            $model=static::find()->where(['dts_id'=>$val['dts_id'],'company_id'=>$user['company_id']])->one();
            $model->dts_name=trim($val['dts_name']);
            $model->dts_address=trim($val['dts_address']);
            $model->u_id=$user['u_id'];
            $model->utime=date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model=new District_slice();
            $model->dt_id=$val['dt_id'];
            $model->dts_name=trim($val['dts_name']);
            $model->dts_address=trim($val['dts_address']);
            $model->c_id = $user['u_id'];
            $model->u_id = $user['u_id'];
            $model->ctime = date('Y-m-d H:i:s');
            $model->utime = date('Y-m-d H:i:s');
            $model->auth_cid = $user['auth_cid'];
            $model->company_id = $user['company_id'];
            $model->auth_rid = $user['auth_rid'];
            $model->auth_sid = $user['auth_sid'];
            $model->auth_aid = $user['auth_aid'];
            $model->auth_baid = $user['auth_baid'];
            $model->is_del = 0;
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }

    }
}
