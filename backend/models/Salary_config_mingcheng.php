<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\Salary_config_mingchengGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Salary_config_mingcheng extends Salary_config_mingchengGii
{
    //获取提成方案
    public function getChildren(){
        return $this->hasMany(Salary_config_mingcheng_yeji::className(),['scm_id'=>'value']);
    }
    public function updateSalary_config($val,$user)
    {
        if(!empty($val['scm_id'])){
            $model=static::find()->where(['scm_id'=>$val['scm_id'],'company_id'=>$user['company_id']])->one();
            $model->fanganmingcheng=trim($val['fanganmingcheng']);
            $model->fanganshuoming=trim($val['fanganshuoming']);
            $model->u_id=$user['u_id'];
            $model->utime=date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model=new Salary_config_mingcheng();
            $model->fanganmingcheng=trim($val['fanganmingcheng']);
            $model->fanganshuoming=trim($val['fanganshuoming']);
            $model->u_id=$user['u_id'];
            $model->c_id = $user['u_id'];
            $model->auth_cid = !empty($user['auth_cid'])?$user['auth_cid']:'';
            $model->auth_rid = !empty($user['auth_rid'])?$user['auth_rid']:'';
            $model->auth_sid = !empty($user['auth_sid'])?$user['auth_sid']:'';
            $model->auth_aid =!empty($user['auth_aid'])?$user['auth_aid']:'';
            $model->auth_baid =!empty($user['auth_baid'])?$user['auth_baid']:'';
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