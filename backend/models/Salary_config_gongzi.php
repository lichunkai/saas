<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\Salary_config_gongziGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Salary_config_gongzi extends Salary_config_gongziGii
{
    public function updateSalary_config_gongzi($val,$user)
    {
        if(!empty($val['scg_id'])){
            $model=static::findOne($val['scg_id']);
            $model->fanganmingcheng=trim($val['fanganmingcheng']);
            $model->jibengongzi=trim($val['jibengongzi']);
            $model->wuxiangeren=trim($val['wuxiangeren']);
            $model->wuxianyijingeren=trim($val['wuxianyijingeren']);
            $model->beizhu=trim($val['beizhu']);
            $model->u_id=$user['u_id'];
            $model->utime=date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model=new Salary_config_gongzi();
            $model->fanganmingcheng=trim($val['fanganmingcheng']);
            $model->jibengongzi=trim($val['jibengongzi']);
            $model->wuxiangeren=trim($val['wuxiangeren']);
            $model->wuxianyijingeren=trim($val['wuxianyijingeren']);
            $model->beizhu=trim($val['beizhu']);
            $model->u_id=$user['u_id'];
            $model->c_id = $user['u_id'];
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