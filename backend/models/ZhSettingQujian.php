<?php

namespace backend\models;

use common\models\gii\ZhSettingQujianGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ZhSettingQujian extends ZhSettingQujianGii
{

    /*
     * 区间配置配置
     */
    public function UpdateSetting($param,$user){
        if(isset($param['qujian_id']) && $param['qujian_id']){
            $model = static::findOne($param['qujian_id']);
            $model->company_id = $user['company_id'];
            $model->qujian_shorthand = $param['qujian_shorthand'];
            $model->qujian_name = $param['qujian_name'];
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model =new ZhSettingQujian();
            $model->qujian_shorthand = $param['qujian_shorthand'];
            $model->qujian_name = $param['qujian_name'];
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
        if(isset($param['qujian_id'])){
            $model = static::findOne($param['qujian_id']);
            $qujian_desp = $model->qujian_desp ? json_decode($model->qujian_desp, true) : [];
            if(isset($param['child_id'])){
                foreach($qujian_desp as $key => $val){
                    if($val['child_id'] == $param['child_id']){
                        $val['child_name'] = $param['child_name'];
                        $val['min'] = $param['min'];
                        $val['max'] = $param['max'];
                    }
                    $qujian_desp[$key] = $val;
                }
            }else{
                $rand = time().'-'.rand();
                $childIds = [];
                foreach($qujian_desp as $key => $val){
                    $childIds[] = $val['child_id'];
                }
                $new = array(
                    'child_id' => $rand,
                    'child_name' => $param['child_name'],
                    'min' => $param['min'],
                    'max' => $param['max']
                );
                $qujian_desp[] = $new;
            }
            $model->qujian_desp = json_encode($qujian_desp);
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
        if(isset($param['qujian_id'])){
            $model = static::findOne($param['qujian_id']);
            $qujian_desp = $model->qujian_desp ? json_decode($model->qujian_desp, true) : [];
            if($param['child_id']){
                $arr = [];
                foreach($qujian_desp as $key => $val){
                    if($val['child_id'] != $param['child_id']){
                        $arr[] = $val;
                    }
                }
                $model->qujian_desp = json_encode($arr);
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

    /**
     * 获取自配置
     */
    public static function getChildSetting($val){
        $cond = is_numeric($val) ? ['qujian_id' => $val] : ['qujian_name' => $val];
        $row =   static::find()->where($cond)->asArray()->one();
        if($row){
            return json_decode($row['qujian_desp'], true);
        }else{
            return [];
        }
    }
}
