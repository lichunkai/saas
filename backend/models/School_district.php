<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\ComVillageGii;
use common\models\gii\School_districtGii;
use Yii;
use yii\base\Exception;
use backend\models\District_region;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class School_district extends School_districtGii
{
    //获取小区
    public function getDistrict_region(){
        return $this->hasOne(ComVillageGii::className(),['rn_id'=>'village_id']);
    }
    public function updateDistrict($val,$user)
    {
        if(!empty($val['sd_id'])){
            $model=static::find()->where(['sd_id'=>$val['sd_id'],'company_id'=>$user['company_id']])->one();
            $model->rn_id=trim($val['rn_id']);
            $model->beizhu=trim($val['beizhu']);
            $model->s_id=trim($val['s_id']);
            $model->u_id=$user['u_id'];
            $model->utime=date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model=new School_district();
            $model->rn_id=trim($val['rn_id']);
            $model->s_id=trim($val['s_id']);
            $model->beizhu=trim($val['beizhu']);
            $model->c_id = $user['u_id'];
            $model->u_id = $user['u_id'];
            $model->company_id = $user['company_id'];
            $model->ctime = date('Y-m-d H:i:s');
            $model->utime = date('Y-m-d H:i:s');
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
