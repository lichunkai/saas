<?php

namespace backend\models;

use common\helps\Tools;
use common\models\gii\Customer_telGii;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Customer_tel extends Customer_telGii
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
    public static function validatePhone($phone,$customer_private,$user,$customer_type=0)
    {

        if($customer_private=='公客'){
            $row = Customer_tel::find()->select('t.*')->alias('t')
                ->leftJoin('zh_customer as c', 'c.customer_uuid=t.customer_uuid')
                ->where(['t.tel_phone'=>$phone,'c.is_del'=>'0','c.company_id'=>self::$user['company_id'],'c.customer_private'=>$customer_private,'c.customer_type'=>$customer_type])->asArray()->all();
            if(empty($row)||!$row){
                return true;
            }else{
                return false;
            }
        }else{
            $row = Customer_tel::find()->select('t.*')->alias('t')
                ->leftJoin('zh_customer as c', 'c.customer_uuid=t.customer_uuid')
                ->where(['t.tel_phone'=>$phone,'c.is_del'=>'0','c.company_id'=>self::$user['company_id'],'c.customer_private'=>$customer_private,'c.c_id'=>$user['u_id'],'c.customer_type'=>$customer_type])->asArray()->all();
            if(empty($row)||!$row){
                return true;
            }else{
                return false;
            }
        }
    }
}
