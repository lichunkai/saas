<?php

namespace backend\models;

use common\models\gii\ZhSettingCustGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ZhSettingCust extends ZhSettingCustGii
{

    /*
     * 普通配置配置
     */
    public function UpdateSetting($param,$user){
        if(isset($param['csetting_id']) && $param['csetting_id']){
            $model = static::findOne($param['csetting_id']);
            $model->company_id = $user['company_id'];
            $model->csetting_shorthand = $param['csetting_shorthand'];
            $model->csetting_type = $param['csetting_type'];
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model =new ZhSettingCust();
            $model->csetting_shorthand = $param['csetting_shorthand'];
            $model->csetting_type = $param['csetting_type'];
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
        if(isset($param['csetting_id'])){
            $model = static::findOne($param['csetting_id']);
            $csetting_child = $model->csetting_child ? json_decode($model->csetting_child, true) : [];
            if(isset($param['child_id'])){
                foreach($csetting_child as $key => $val){
                    if($val['child_id'] == $param['child_id']){
                        $val['child_name'] = $param['child_name'];
                        $val['child_type'] = $param['child_type'];
                        $val['unit'] = $param['unit'];
                        $val['required'] = $param['required'];
                        $val['sort'] = $param['sort'];
                    }
                    $csetting_child[$key] = $val;
                }
            }else{
                $rand = time().'-'.rand();
                $childIds = [];
                foreach($csetting_child as $key => $val){
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
                array_push($csetting_child, $new);
            }
            $model->csetting_child = json_encode($csetting_child);
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            //var_dump($model->getErrors());
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
        if(isset($param['csetting_id'])){
            $model = static::findOne($param['csetting_id']);
            $csetting_child = $model->csetting_child ? json_decode($model->csetting_child, true) : [];
            if($param['child_id']){
                $arr = [];
                foreach($csetting_child as $key => $val){
                    if($val['child_id'] != $param['child_id']){
                        $arr[] = $val;
                    }
                }
                $model->csetting_child = json_encode($arr);
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
