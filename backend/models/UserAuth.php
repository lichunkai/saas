<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\UserAuthGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserAuth extends UserAuthGii
{
    /**
     *
     */
    public static function getUserAuth($u_id,$type=0)
    {
        $selelct = 'a.u_id,a.p_id,a.data_range,b.p_name,b.p_desp,b.p_url,b.system_type,b.p_type as type,b.p_ico,b.p_pid,';
        $auth = UserAuth::find()->alias('a')->select($selelct)->innerJoin('zh_purview as b','a.p_id=b.p_id')->where(['a.u_id'=>$u_id,'b.is_del'=>0])->asArray()->all();

        $selelct = 'a.u_id,a.p_id,a.data_range,b.p_name,b.p_desp,b.p_url,b.system_type,b.p_type as type,b.p_ico,b.p_pid';
        $menuauth = UserAuth::find()->alias('a')->select($selelct)->innerJoin('zh_purview as b','a.p_id=b.p_id')->where(['a.u_id'=>$u_id,'b.is_auth'=>1,'b.is_del'=>0])->asArray()->all();

        $dynamicMenu = Yii::$app->params['dynamicMenu'];
        $menulist = [];
        foreach($menuauth as $key =>$item){
            foreach ($dynamicMenu as $k => $menu){
                if($menu['type'] == 0 || $menu['type'] == $type){
                    if($item['p_url'] == $menu['url'] || (is_array($menu['url']) && in_array($item['p_url'],$menu['url']))){
                        $menulist[] = $menu;
                    }
                }
            }
        }
        $menulist = Tools::arrayUnsetRepeat($menulist,'name');
        return [$auth,$menulist];
    }

    /**
     * 更新用户权限
     * @param $param
     * @return bool
     */
    public static function UpdateAuth($param,$user){
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            //更新角色权限
            $result = UserAuth::deleteAll(['u_id' => $param['u_id']]);
            if($result === false){
                $transaction->rollBack();
                return false;
            }

            foreach ($param['auths'] as $key =>$auth){
                if($auth !== '0' && $auth !== 'false') {
                    $authmodel = new UserAuth();
                    $authmodel->u_id = $param['u_id'];
                    $authmodel->p_id = $key;
                    $authmodel->data_range = $auth === 'true'? 6 :$auth;
                    $authmodel->company_id = $user['company_id'];
                    $authmodel->cid = $user['u_id'];
                    $authmodel->ctime = date('Y-m-d H:i:s');
                    $auth_result = $authmodel->save();
                    if (!$auth_result) {
                        $transaction->rollBack();
                        return false;
                    }
                }
            }

            $otherPurview = Purview::find()->where(['is_auth'=>0,'is_del'=>0])->andWhere(['<>','p_pid',0])->asArray()->all();
            foreach ($otherPurview as $kk => $others){
                $authmodel = new UserAuth();
                $authmodel->u_id = $param['u_id'];
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

            $transaction->commit();
            return true;
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    public static function getUisByUserPurview($p_id,$user){
        foreach ($user['auths'] as $item){
            if($item['p_id']==$p_id){
                //if($item['p_type']==3){
                //	return false;
                //}else if($item['p_type']==1){
                //	return [$user['u_id']];
                //}else{
                //查看本人所在部门所有人
                $result = User::find()->where(['company_id'=>$user['company_id'],'u_dept_id'=>$user['u_dept_id']])->asArray()->all();
                $usersArr =[];
                foreach ($result as $item){
                    $usersArr[] = $item['u_id'];
                }
                return $usersArr;
                //}
            }
        }
        return false;
    }

}
