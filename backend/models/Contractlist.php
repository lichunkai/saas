<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\ContractlistGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Contractlist extends  ContractlistGii
{
    private static $user;
    private static $_tokens;
    public function init(){
        parent::init();
        $headeToken =  Yii::$app->request->headers->get('X-Access-Token');
        $getToken =  Yii::$app->request->get('token');
        $postToken =  Yii::$app->request->get('token');
        if($headeToken&&!empty($headeToken)){
            self::$_tokens=$headeToken;
        }elseif($getToken&&!empty($getToken)){
            self::$_tokens=$getToken;
        }elseif($postToken&&!empty($postToken)){
            self::$_tokens=$postToken;
        }
        self::$user = json_decode(Yii::$app->redis->get(self::$_tokens),'true');
    }
    // 批量生成合同
    public static function pl_scht($post,$user)
    {

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        try {
            $htjh['htid']='';
            $htjh['bianhao']='';
           foreach($post['htmb'] as $v){
               $model = new Contractlist();
               $model->mingcheng=$v['con_templet_name'];
               $model->fy_khxm=$post['fy_khxm'];
               $model->ky_khxm=$post['ky_khxm'];
               $model->fy_bianhao=$post['fy_bianhao'];
               $model->ky_bianhao=$post['ky_bianhao'];
               $model->fy_xb=$post['fy_xb'];
               $model->ky_xb=$post['ky_xb'];
               $model->jingjiren=$post['u_id'];
               $model->bumen_id=$post['u_dept_id'];
               $model->htnr=$v['con_templet_content'];
               $model->htqz=$v['con_templet_prefix'];
               $model->zhuangtai=$post['zhuangtai'];
               $model->c_id = $user['u_id'];
               $model->u_id = $user['u_id'];
               $model->company_id = self::$user['company_id'];
               $model->ctime = date('Y-m-d H:i:s');
               $model->utime = date('Y-m-d H:i:s');
               $model->auth_cid = $user['auth_cid'];
               $model->auth_rid = $user['auth_rid'];
               $model->auth_sid = $user['auth_sid'];
               $model->auth_aid = $user['auth_aid'];
               $model->auth_baid = $user['auth_baid'];
               $htbh_z = Yii::$app->redis->get('htbh_z');
               if (empty($htbh_z)) {
                   $htbh_z = 1;
                   Yii::$app->redis->set('htbh_z', $htbh_z);
               } else {
                   $htbh_z = $htbh_z + 1;
                   Yii::$app->redis->set('htbh_z', $htbh_z);
               }
               $bianhao= $v['con_templet_prefix'].'-' . date('ymd') . '-' . str_pad($htbh_z, 4, "0", STR_PAD_LEFT);
               $model->bianhao=$bianhao;
                if(!$model->save()){
                    $transaction->rollBack();
                    return false;
                }
               $htjh['htid'].=$model->con_id.';';
               $htjh['bianhao'].=$model->bianhao.';';
           }
            $transaction->commit();
            return $htjh;
        }catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
    // 批量生成通过
    public static function pl_scht_tg($param)
    {
        $array = explode(";",$param);
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        if(!empty($array)){
            try {
                foreach($array as $v){
                    if(!empty($v)){
                        $c_data=Contractlist::find()->where(['con_id'=>$v,'company_id'=>self::$user['company_id']])->one();
                        $c_data->zhuangtai='进行中';
                        $c_data->utime = date('Y-m-d H:i:s');
                        if(!$c_data->save()){
                            $transaction->rollBack();
                            return false;
                        }
                    }
                }
                $transaction->commit();
                return true;
            }catch (Exception $e) {
                $transaction->rollBack();
                return false;
            }
        }


    }
    // 不通过批量删除
    public static function pl_scht_bh($param)
    {
        $array = explode(";",$param);
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();//开启事物
        if(!empty($array)){
            try {
                foreach($array as $v){
                    if(!empty($v)){
                        $c_data=Contractlist::find()->where(['con_id'=>$v,'company_id'=>self::$user['company_id']])->one();
                        $c_data->is_del=1;
                        $c_data->utime = date('Y-m-d H:i:s');
                        if(!$c_data->save()){
                            $transaction->rollBack();
                            return false;
                        }
                    }
                }
                $transaction->commit();
                return true;
            }catch (Exception $e) {
                $transaction->rollBack();
                return false;
            }
        }


    }

}
