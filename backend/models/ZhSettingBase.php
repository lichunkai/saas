<?php

namespace backend\models;

use common\models\gii\ZhSettingBaseGii;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ZhSettingBase extends ZhSettingBaseGii
{

    /*
     * 普通配置配置
     */
    public function UpdateSetting($param,$user){
        if(isset($param['base_id']) && $param['base_id']){
            $model = static::findOne($param['base_id']);
            $model->base_shorthand = $param['base_shorthand'];
            $model->base_name = $param['base_name'];
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model =new ZhSettingBase();
            $model->base_shorthand = $param['base_shorthand'];
            $model->base_name = $param['base_name'];
            $model->utime = date('Y-m-d H:i:s');
            $model->ctime = date('Y-m-d H:i:s');
            $model->company_id = $user['company_id'];
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
        if(isset($param['base_id'])){
            $model = static::findOne($param['base_id']);
            $base_desp = $model->base_desp ? json_decode($model->base_desp, true) : [];
            if(isset($param['child_id'])){
                foreach($base_desp as $key => $val){
                    if($val['child_id'] == $param['child_id']){
                        $val['child_name'] = $param['child_name'];
                        //$val['sort'] = $param['sort'];
                    }
                    $base_desp[$key] = $val;
                }
            }else{
                $rand = time().'-'.rand();
                $childIds = [];
                foreach($base_desp as $key => $val){
                    $childIds[] = $val['child_id'];
                }
                $new = array(
                    'child_id' => $rand,
                    'child_name' => $param['child_name'],
                    //'sort' => $param['sort']
                );
                $base_desp[] = $new;
            }
            $model->base_desp = json_encode($base_desp);
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
        if(isset($param['base_id'])){
            $model = static::findOne($param['base_id']);
            $base_desp = $model->base_desp ? json_decode($model->base_desp, true) : [];
            if($param['child_id']){
                $arr = [];
                foreach($base_desp as $key => $val){
                    if($val['child_id'] != $param['child_id']){
                        $arr[] = $val;
                    }
                }
                $model->base_desp = json_encode($arr);
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


	/***
	 * 获取配置
	 * @param $base_id int 配置ID
	 * @return array
	 */
	public static function getBaseSettings($key,$company_id=0){
		$row =   static::find()->where(['base_shorthand'=>$key,'company_id'=>$company_id])->asArray()->one();
		$base_desp = json_decode($row['base_desp'],true);
		if($row&&!empty($base_desp)&&is_array($base_desp)){
			$data = [];
			foreach ($base_desp as $item){
				$data[] = $item['child_name'];
			}
			return $data;
		}else{
			return [];
		}

	}

	/***
     * 获取子项
     */
	public static function getBaseChildSettings($val){
	    $cond = is_numeric($val) ? ['base_id' => $val] : ['base_name' => $val];
        $row =  static::find()->where($cond)->asArray()->one();
        if($row){
            return json_decode($row['base_desp'], true);
        }else{
            return [];
        }
    }
}
