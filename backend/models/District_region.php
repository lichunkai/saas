<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\District_regionGii;
use backend\models\District;
use backend\models\District_slice;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class District_region extends District_regionGii
{
    //获取行政区
    public function getDistrict(){
        return $this->hasOne(District::className(),['dt_id'=>'dt_id']);
    }
    //获取片区
    public function getDistrict_slice(){
        return $this->hasOne(District_slice::className(),['dts_id'=>'dts_id']);
    }
    public function updateDistrict_region($val,$user)
    {
        if(!empty($val['rn_id'])){
            $model=static::find()->where(['rn_id'=>$val['rn_id'],'company_id'=>$user['company_id']])->one();
            $model->dt_id=intval($val['dt_id']);
            $model->dts_id=intval($val['dts_id']);
            $model->rn_name=trim($val['rn_name']);
            $model->rn_price=$val['rn_price'];
            $model->rn_address=trim($val['rn_address']);
            $model->u_id=$user['u_id'];
            $model->utime=date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model=new District_region();
            $model->dt_id=intval($val['dt_id']);
            $model->dts_id=intval($val['dts_id']);
            $model->rn_name=trim($val['rn_name']);
            $model->rn_price=$val['rn_price'];
            $model->rn_address=trim($val['rn_address']);
            $model->c_id = $user['u_id'];
            $model->u_id = $user['u_id'];
            $model->ctime = date('Y-m-d H:i:s');
            $model->utime = date('Y-m-d H:i:s');
            $model->company_id = $user['company_id'];
            $model->auth_cid = $user['auth_cid'];
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
