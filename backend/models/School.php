<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\SchoolGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class School extends SchoolGii
{

    public function updateDistrict($val,$user)
    {
        if(!empty($val['s_id'])){
            $model=static::find()->where(['s_id'=>$val['s_id'],'company_id'=>$user['company_id']])->one();
            $model->s_name=trim($val['s_name']);
            $model->s_address=trim($val['s_address']);
            $model->u_id=$user['u_id'];
            $model->utime=date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model=new School();
            $model->s_name=trim($val['s_name']);
            $model->s_address=trim($val['s_address']);
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
