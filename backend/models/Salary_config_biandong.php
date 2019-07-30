<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\Salary_config_biandongGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Salary_config_biandong extends Salary_config_biandongGii
{
    public function updateSalary_config_biandong($val,$user)
    {
        if(!empty($val['scb_id'])){
            $model=static::find()->where(['scb_id'=>$val['scb_id'],'company_id'=>$user['company_id']])->one();
            $model->biandongleixing=trim($val['biandongleixing']);
            $model->beizhu=trim($val['beizhu']);
            $model->zhengjianleixing=trim($val['zhengjianleixing']);
            $model->jine=trim($val['jine']);
            $model->biandongriqi=trim($val['biandongriqi']);
            $model->u_id=$user['u_id'];
            $model->utime=date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model=new Salary_config_biandong();
            $model->bumen_id= !empty($val['bumen_id'][count($val['bumen_id'])-1]['value'])?$val['bumen_id'][count($val['bumen_id'])-1]['value']:$val['bumen_id'][count($val['bumen_id'])-1];
            $model->renyuan=trim($val['renyuan']);
            $model->biandongleixing=trim($val['biandongleixing']);
            $model->beizhu=trim($val['beizhu']);
            $model->zhengjianleixing=trim($val['zhengjianleixing']);
            $model->jine=trim($val['jine']);
            $model->biandongriqi=trim($val['biandongriqi']);
            $model->u_id=$user['u_id'];
            $model->c_id = $user['u_id'];
            $model->company_id = $user['company_id'];
            $model->auth_cid = !empty($user['auth_cid'])?$user['auth_cid']:'';
            $model->auth_rid = !empty($user['auth_rid'])?$user['auth_rid']:'';
            $model->auth_sid = !empty($user['auth_sid'])?$user['auth_sid']:'';
            $model->auth_aid =!empty($user['auth_aid'])?$user['auth_aid']:'';
            $model->auth_baid =!empty($user['auth_baid'])?$user['auth_baid']:'';
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