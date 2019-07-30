<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\Salary_config_mingcheng_yejiGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Salary_config_mingcheng_yeji extends Salary_config_mingcheng_yejiGii
{
    public function updateSalary_config_yeji($val,$user)
    {
        if(!empty($val['scmy_id'])){
            $model=static::find()->where(['scmy_id'=>$val['scmy_id'],'company_id'=>$user['company_id']])->one();
            $model->zhuangtai=1;
            $yejifencheng=array();
            if(!empty($val['name'])){
                for( $i=0;$i<count($val['name']);$i++){
                    if(!empty($val['name'][$i])){
                        $yejifencheng[$i]['name'] = $val['name'][$i];
                        $yejifencheng[$i]['fencheng'] = $val['fencheng'][$i];
                    }

                }
            }
            $model->yeji=json_encode($yejifencheng);
            $model->u_id=$user['u_id'];
            $model->utime=date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model=new Salary_config_mingcheng_yeji();
            $model->yejimingcheng=trim($val['yejimingcheng']);
            $model->scm_id=intval($val['scm_id']);
            $model->u_id=$user['u_id'];
            $model->c_id = $user['u_id'];
            $model->auth_cid = !empty($user['auth_cid'])?$user['auth_cid']:'';
            $model->auth_rid = !empty($user['auth_rid'])?$user['auth_rid']:'';
            $model->auth_sid = !empty($user['auth_sid'])?$user['auth_sid']:'';
            $model->auth_aid =!empty($user['auth_aid'])?$user['auth_aid']:'';
            $model->company_id = $user['company_id'];
            $model->auth_baid =!empty($user['auth_baid'])?$user['auth_baid']:'';
            $model->ctime = date('Y-m-d H:i:s');

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