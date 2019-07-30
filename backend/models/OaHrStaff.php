<?php

namespace backend\models;

use common\models\gii\OaHrStaffGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OaHrStaff extends OaHrStaffGii
{

    /*
     * 普通配置配置
     */
    public function UpdateHr($param,$user){
        if(isset($param['hr_id'])){
            $model = static::findOne($param['hr_id']);
            $model->u_id = $user['u_id'];
        }else{
            $model = new OaQingjia();
            $model->c_id = $user['u_id'];
            $model->ctime = date('Y-m-d H:i:s');
        }
        $model->company_id = $user['company_id'];
        //$attrs = $model->hasAttribute();

        $fields = $model->attributeLabels();

        foreach($param as $key => $item){
            if($item=='NULL'||$item == 'undefined'||$item=='******'){ //过虑一些不正确的参数
                continue;
            }
            foreach ($fields as $k => $v) {
                if ($key == $k) {
                    $model->$k = $item;
                }
            }
        }
        if($model->save()){
            return true;
        }else{
            return false;
        }
    }
}
