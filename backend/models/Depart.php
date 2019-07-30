<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\DepartGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Depart extends DepartGii
{
    /**
     * 添加部门
     * @param $param
     * @param $user
     * @return bool
     */
    public function UpdateDepart($param,$user)
    {
        if(isset($param['d_id']) && $param['d_id']){
            $model = static::findOne($param['d_id']);
            $model->d_name = trim($param['d_name']);
//            $model->d_type = $param['d_type'];
            $model->d_principal_id = $param['d_principal_id'];
            $model->d_principal = $param['d_principal'];
            $model->d_address = trim($param['d_address']);
            $model->d_phone = trim($param['d_phone']);
            $model->d_pid = $param['d_pid'];
            $model->d_pid_name = $param['d_pid_name'];
            //$model->d_sort = $param['d_sort'];
            //坐标生成
            if(isset($param['d_address']) && $param['d_address']){
                $bdmap_url = Tools::buildGeoCodingUrl('json', trim($param['d_address']));
                $result_arr = json_decode(Tools::curl_get($bdmap_url), true);
                if ($result_arr['status'] == 0) {
                    $model->d_location = $result_arr["result"]["location"]["lat"] . "," . $result_arr["result"]["location"]["lng"];
                }
            }
            //$model->path = isset($param['path']) ? trim($param['path']):'';
            $model->d_level = isset($param['d_level']) ? $param['d_level']:'';
            $model->uid = $user['u_id'];
            $model->utime = date('Y-m-d H:i:s');
            $result = $model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $model = new Depart();
            $model->d_name = trim($param['d_name']);
//            $model->d_type = $param['d_type'];
            $model->d_principal_id = $param['d_principal_id'];
            $model->d_principal = $param['d_principal'];
            $model->d_address = trim($param['d_address']);
            $model->d_phone = trim($param['d_phone']);
            $model->d_pid = $param['d_pid'];
            $model->d_pid_name = $param['d_pid_name'];
           // $model->d_sort = $param['d_sort'];
            //坐标生成
            if(isset($param['d_address']) && $param['d_address']){
                $bdmap_url = Tools::buildGeoCodingUrl('json', trim($param['d_address']));
                $result_arr = json_decode(Tools::curl_get($bdmap_url), true);
                if ($result_arr['status'] == 0) {
                    $model->d_location = $result_arr["result"]["location"]["lat"] . "," . $result_arr["result"]["location"]["lng"];
                }
            }
            $model->d_level = isset($param['d_level']) ? $param['d_level']:'';
            $model->cid = $user['u_id'];
            $model->uid = $user['u_id'];
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

    /**
     * 删除部门
     * @param $param
     * @param $user
     * @return bool
     */
    public function DeleteDepart($subnode,$user){

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            foreach ($subnode  as $key => $item){
                $model = static::findOne($item);
                $model->is_del = 1;
                $model->uid = $user['u_id'];
                $model->utime = date('Y-m-d H:i:s');
                if(!$model->update()){
                    $transaction->rollBack();
                    return false;
                }
                //清除角色权限中选中该权限的
//                $roleauths = RoleAuth::find()->where(['p_id' => $item])->all();
//                foreach ($roleauths as $k => $roleauth){
//                    if($roleauth->delete() === false){
//                        $transaction->rollBack();
//                        return false;
//                    }
//                }
                //更新掉用户部门数据
                $result_users = User::find()->where(['u_dept_id' => $item])->all();
                foreach ($result_users as $k => $result_user){
                    $result_user->u_dept_id = 0;
                    $result_user->uid = $user['u_id'];
                    $result_user->utime = date('Y-m-d H:i:s');
                    if($result_user->update() === false){
                        $transaction->rollBack();
                        return false;
                    }
                }
            }
            $transaction->commit();
            return true;
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}
