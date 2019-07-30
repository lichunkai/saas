<?php

namespace backend\models;

use common\models\gii\RoleGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Role extends RoleGii
{
    /**
     * 添加角色
     * @param $param
     * @return bool
     */
    public function UpdateRole($param,$user){
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            if(isset($param['role_id']) && $param['role_id']){
                $model = static::findOne($param['role_id']);
                $model->role_name = $param['role_name'];
                $model->role_type = $param['role_type'];
                $model->role_desp = $param['role_desp'];
                $model->uid = $user['u_id'];
                $model->utime = date('Y-m-d H:i:s');
                $result = $model->save();
                if (!$result) {
                    $transaction->rollBack();
                    return false;
                }
                $role_id = $param['role_id'];
                //删除原先角色权限
                $result = RoleAuth::deleteAll(['r_id' => $param['role_id']]);
                if($result === false){
                    $transaction->rollBack();
                    return false;
                }

            }else{
                $model = new Role();
                $model->role_name = $param['role_name'];
                $model->role_type = $param['role_type'];
                $model->role_desp = $param['role_desp'];
                $model->company_id = $user['company_id'];
                $model->cid = $user['u_id'];
                $model->uid = $user['u_id'];
                $model->ctime = date('Y-m-d H:i:s');
                $model->utime = date('Y-m-d H:i:s');
                $model->is_del = 0;
                $result = $model->save();
                $role_id = Yii::$app->db->getLastInsertID();
                if(!$result){
                    $transaction->rollBack();
                    return false;
                }
            }
            //添加角色权限
            foreach ($param['auths'] as $key =>$auth){
                if($auth !== '0' && $auth !== 'false'){
                    $authmodel = new RoleAuth();
                    $authmodel->r_id = $role_id;
                    $authmodel->p_id = $key;
                    $authmodel->data_range = $auth === 'true'? 6 :$auth;
                    $authmodel->company_id = $user['company_id'];
                    $authmodel->cid = $user['u_id'];
                    $authmodel->ctime = date('Y-m-d H:i:s');
                    $auth_result = $authmodel->save();
                    if(!$auth_result){
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            $otherPurview = Purview::find()->where(['is_auth'=>0,'is_del'=>0])->andWhere(['<>','p_pid',0])->asArray()->all();
            foreach ($otherPurview as $kk => $others){
                $authmodel = new RoleAuth();
                $authmodel->r_id = $role_id;
                $authmodel->p_id = $others['p_id'];
                $authmodel->data_range = 6;
                $authmodel->company_id = $user['company_id'];
                $authmodel->cid = $user['u_id'];
                $authmodel->ctime = date('Y-m-d H:i:s');
                $auth_result = $authmodel->save();
                if (!$auth_result) {
                    $transaction->rollBack();
                    return false;
                }
            }

            //更新角色下用户权限
            $usersByrole = User::find()->where(['u_role_id'=>$role_id,'is_del'=>0])->asArray()->all();
            if($usersByrole){
                foreach ($usersByrole as $userByuser){
                    $userparam['u_id'] = $userByuser['u_id'];
                    $userparam['auths'] = $param['auths'];
                     $result = UserAuth::UpdateAuth($userparam,$user);
                     if($result === false){
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


    /**
     * 删除角色
     * @param $id
     * @param $user
     * @return bool
     */
    public function DeleteRole($id,$user){

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            $role = static::findOne($id);
            $role->is_del = 1;
            $role->uid = $user['u_id'];
            $role->utime = date('Y-m-d H:i:s');
            if(!$role->update()){
                $transaction->rollBack();
                return false;
            }

            //清除角色权限中选中该权限的
            $roleauths = RoleAuth::find()->where(['r_id' => $id])->all();
            foreach ($roleauths as $k => $roleauth){
                if($roleauth->delete() === false){
                    $transaction->rollBack();
                    return false;
                }
            }

            //更新掉角色部门数据
            $result_users = User::find()->where(['u_role_id' => $id])->all();
            foreach ($result_users as $k => $result_user){
                $result_user->u_role_id = 0;
                $result_user->uid = $user['u_id'];
                $result_user->utime = date('Y-m-d H:i:s');
                if($result_user->update() === false){
                    $transaction->rollBack();
                    return false;
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
