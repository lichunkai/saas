<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\DistrictGii;
use backend\models\District_slice;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class District extends DistrictGii
{
    //获取片区
    public function getChildren(){
        return $this->hasMany(District_slice::className(),['dt_id'=>'value']);
    }
    public function updateDistrict($val,$user)
    {

        if(!empty($val['dt_id'])){
            $model=static::find()->where(['dt_id'=>$val['dt_id'],'company_id'=>$user['company_id']])->one();
            $model->dt_province_id=$val['dt_province_id'];
            $model->dt_province_name=trim($val['dt_province_name']);
            $model->dt_city_id=$val['dt_city_id'];
            $model->dt_city_name=trim($val['dt_city_name']);
            $model->dt_area_id=$val['dt_area_id'];
            $model->dt_area_name=trim($val['dt_area_name']);
            $model->u_id=$user['u_id'];
            $model->utime=date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model=new District();
            $model->dt_province_id=$val['dt_province_id'];
            $model->dt_province_name=trim($val['dt_province_name']);
            $model->dt_city_id=$val['dt_city_id'];
            $model->dt_city_name=trim($val['dt_city_name']);
            $model->dt_area_id=$val['dt_area_id'];
            $model->dt_area_name=trim($val['dt_area_name']);
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
