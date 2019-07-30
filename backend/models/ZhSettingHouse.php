<?php

namespace backend\models;

use common\models\gii\ZhSettingHouseGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ZhSettingHouse extends ZhSettingHouseGii
{

    /*
     * 普通配置配置
     */
    public function UpdateSetting($param,$user){
        if(isset($param['hsetting_id']) && $param['hsetting_id']){
            $model = static::findOne($param['hsetting_id']);
            $model->hsetting_shorthand = $param['hsetting_shorthand'];
            $model->hsetting_type = $param['hsetting_type'];
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model =new ZhSettingHouse();
            $model->hsetting_shorthand = $param['hsetting_shorthand'];
            $model->hsetting_type = $param['hsetting_type'];
            $model->utime = date('Y-m-d H:i:s');
            $model->ctime = date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }
    }

    /*
     * 子配置项更新
     */
    public function UpdateChildSetting($param, $user){
        if(isset($param['hsetting_id'])){
            $model = static::findOne($param['hsetting_id']);
            $hsetting_child = $model->hsetting_child ? json_decode($model->hsetting_child, true) : [];
            if(isset($param['child_id'])){
                foreach($hsetting_child as $key => $val){
                    if($val['child_id'] == $param['child_id']){
                        $val['child_name'] = $param['child_name'];
                        $val['child_type'] = $param['child_type'];
                        $val['unit'] = $param['unit'];
                        $val['required'] = $param['required'];
                        $val['sort'] = $param['sort'];
                    }
                    $hsetting_child[$key] = $val;
                }
            }else{
                $rand = time().'-'.rand();
                $childIds = [];
                foreach($hsetting_child as $key => $val){
                    $childIds[] = $val['child_id'];
                }
                $new = array(
                    'child_id' => $rand,
                    'child_name' => $param['child_name'],
                    'child_type' => $param['child_type'],
                    'unit' => $param['unit'],
                    'required' => $param['required'],
                    'sort' => $param['sort']
                );
                //$hsetting_child[] = $new;
                array_push($hsetting_child, $new);
            }
            $model->hsetting_child = json_encode($hsetting_child);
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /*
     * 子配置删除
     */
    public function DelChildSetting($param, $user){
        if(isset($param['hsetting_id'])){
            $model = static::findOne($param['hsetting_id']);
            $hsetting_child = $model->hsetting_child ? json_decode($model->hsetting_child, true) : [];
            if($param['child_id']){
                $arr = [];
                foreach($hsetting_child as $key => $val){
                    if($val['child_id'] != $param['child_id']){
                        $arr[] = $val;
                    }
                }
                $model->hsetting_child = json_encode($arr);
                $model->utime = date('Y-m-d H:i:s');
                $result = $model->save();
                if($result){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
