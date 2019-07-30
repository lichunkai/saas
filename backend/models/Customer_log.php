<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\Customer_logGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Customer_log extends Customer_logGii
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
    //日志
    public function log($customer_uuid,$lo_type,$lo_content,$user=array()){
        $Customer_log = new Customer_log();
        $Customer_log->c_id =!empty($user['u_id'])?$user['u_id']:'' ;
        $Customer_log->customer_uuid = $customer_uuid;
        $Customer_log->cl_type = $lo_type;
        $Customer_log->u_id = !empty($user['u_id'])?$user['u_id']:'';
        $Customer_log->ctime = date('Y-m-d H:i:s');
        $Customer_log->utime =date('Y-m-d H:i:s');
        $Customer_log->cl_type = $lo_type;
        $Customer_log->company_id = !empty($user['company_id'])?$user['company_id']:'' ;
        $Customer_log->cl_content = $lo_content;
        $Customer_log->save();
        return true;
    }

}
