<?php

namespace backend\models;

use common\models\gii\ZhSettingRequiredGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ZhSettingRequired extends ZhSettingRequiredGii
{

    /*
     * 必填项配置修改
     */
    public function UpdateSetting($param,$user){
        if(isset($param['types']) && $param['types']) {
            $errorCount = 0;
            for($i=0; $i<count($param['types']); $i++){
                $rsetting_type = $param['types'][$i];
                $res = ZhSettingRequired::find()->where(['company_id'=>$user['company_id'],'rsetting_type' => $rsetting_type])->asArray()->one();
                //echo ZhSettingRequired::find()->where(['company_id'=>$user['company_id'],'rsetting_type' => $rsetting_type])->createCommand()->getRawSql();
                if($res){
                    $rsetting_desp_arr = isset($param[$rsetting_type]) ? $param[$rsetting_type] : false;
                    $rsetting_desp = $rsetting_desp_arr ? join(',', $rsetting_desp_arr) : '';
                    $res['rsetting_id'] += 0;
                    $model = static::findOne($res['rsetting_id']);
                    $model->rsetting_desp = $rsetting_desp;
                    $model->company_id = $user['company_id'];
                    $model->utime = date('Y-m-d H:i:s');
                    $result = $model->save();
                    if(!$result){
                        $errorCount++;
                    }
                }
            }
            return $errorCount ? false : true;
            //$model->rsetting_type = $param['rsetting_type'];
        }else{
            return false;
        }
    }

    /**
     * @param $param
     * @param $user
     * @return bool
     */
    public function CopyOptionsSetting($param, $user){
        if(isset($param['src_type']) && $param['src_type'] && isset($param['dst_type']) && $param['dst_type']){
            $res = ZhSettingRequired::find()->where(['company_id'=>$user['company_id'],'rsetting_type' => $param['src_type']])->asArray()->one();
            $model = new ZhSettingRequired();
            $model->rsetting_type = $param['dst_type'];
            $model->rsetting_options = $res['rsetting_options'];
            $model->rsetting_desp = $res['rsetting_desp'];
	        $model->company_id = $user['company_id'];
            $model->ctime = date('Y-m-d H:i:s');
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
     * 必填项更新
     */
    public function UpdateOptionsSetting($param, $user){
        if(isset($param['rsetting_type']) && $param['rsetting_type']){
            $res = ZhSettingRequired::find()->where(['rsetting_type' => $param['rsetting_type'], 'company_id'=>$user['company_id']])->asArray()->one();
            if($res){
                $res['rsetting_id'] += 0;
                $model = static::findOne($res['rsetting_id']);
                $rsetting_options = $model->rsetting_options ? json_decode($model->rsetting_options, true) : [];
                if(isset($param['option_id']) && $param['option_id']){
                    foreach($rsetting_options as $key => $val){
                        if($val['option_value'] == $param['option_value'] && $val['option_id'] != $param['option_id']){
                            return false;
                        }
                    }
                    foreach($rsetting_options as $key => $val){
                        if($val['option_id'] == $param['option_id']){
                            $val['option_value'] = $param['option_value'];
                            $val['option_text'] = $param['option_text'];
                        }
                        $rsetting_options[$key] = $val;
                    }
                }else{
                    $rand = time().'-'.rand();
                    $childIds = [];
                    foreach($rsetting_options as $key => $val){
                        $childIds[] = $val['option_id'];
                    }
                    $new = array(
                        'option_id' => $rand,
                        'option_value' => $param['option_value'],
                        'option_text' => $param['option_text'],
                        //'sort' => $param['sort']
                    );
                    $rsetting_options[] = $new;
                }
                $model->rsetting_options = json_encode($rsetting_options,JSON_UNESCAPED_UNICODE );
                $model->utime = date('Y-m-d H:i:s');
                $result = $model->save();
                if($result){
                    return true;
                }else{
                    return false;
                }
            }else{
                $model = new ZhSettingRequired();
                $model->rsetting_type = $param['rsetting_type'];

                $rand = time().'-'.rand();
                $new = array(
                    'option_id' => $rand,
                    'option_value' => $param['option_value'],
                    'option_text' => $param['option_text'],
                    //'sort' => $param['sort']
                );
                $rsetting_options = array($new);
                $model->rsetting_options = json_encode($rsetting_options,JSON_UNESCAPED_UNICODE );
                $model->ctime = date('Y-m-d H:i:s');
                $model->utime = date('Y-m-d H:i:s');
                $model->company_id = $user['company_id'];
                $result = $model->save();
                if($result){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    /*
     * 必填项删除
     */
    public function DelOptionsSetting($param, $user){
        if(isset($param['rsetting_id'])){
            $model = static::findOne($param['rsetting_id']);
            $rsetting_options = $model->rsetting_options ? json_decode($model->rsetting_options, true) : [];
            if($param['option_id']){
                $arr = [];
                foreach($rsetting_options as $key => $val){
                    if($val['option_id'] != $param['option_id']){
                        $arr[] = $val;
                    }
                }
                $model->rsetting_options = json_encode($arr,JSON_UNESCAPED_UNICODE );
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
