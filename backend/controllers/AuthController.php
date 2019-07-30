<?php
namespace backend\controllers;

use backend\models\SystemLog;
use common\helps\Tools;
use common\models\CommSetting;
use Yii;
use common\controllers\CommonController;
use common\models\ApiReturn;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\Controller;

/**
 * Common controller
 */
class AuthController extends CommonController
{
    protected $_user = null;
    protected $_tokens = null;
    protected $_range = null;
    protected $_setting = null;

    public function __construct($id, Module $module, array $config = [])
    {
        // 添加跨域允许
        $headers = Yii::$app->response->headers;
        $headers->set('Access-Control-Allow-Origin', '*');
        $headers->set('Access-Control-Allow-Headers', 'X-Access-Token, Content-Type');
        $headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        parent::__construct($id, $module, $config);
    }


    public function beforeAction($action)
    {   //var_dump($this->id);die;
        $url = $this->id.'/'.$action->id;
        $noauth_action = ['customer/insms','login/login','login/logout','login/getlicense','house/detail_xcx','house/housecard','login/setlicense','company/register','company/sendsms','payment/notify','payment/qrcode','login/companylist'];
        //var_dump(Yii::$app->redis->get('0000d020caa5d22d56ab9fe20d63e387'));die;
        if(!in_array($url,$noauth_action)){
            $headeToken =  Yii::$app->request->headers->get('X-Access-Token');
            $getToken =  Yii::$app->request->get('token');
            $postToken =  Yii::$app->request->get('token');
            if($headeToken&&!empty($headeToken)){
                $this->_tokens=$headeToken;
            }elseif($getToken&&!empty($getToken)){
                $this->_tokens=$getToken;
            }elseif($postToken&&!empty($postToken)){
                $this->_tokens=$postToken;
            }else{
                Yii::$app->response->data = ApiReturn::needAuth('请先登录1');
                return false;
            }
            //var_dump(Yii::$app->redis->get($this->_tokens));
            $this->_user = json_decode(Yii::$app->redis->get($this->_tokens),'true');
            if(!$this->_user){
                Yii::$app->response->data = ApiReturn::needAuth('请先登录2');
                return false;
            }

            //判断权限
//            $auths = $this->_user['auths'];
//            $urls = ArrayHelper::getColumn($auths,'p_url');
//            if(!in_array($url,$urls)){//var_dump($url);die;
//                Yii::$app->response->data = ApiReturn::forbidden('请先授权');
//                return false;
//            }
//
//            foreach ($auths as $key => $auth){
//                if($auth['p_url'] == $url){
//                    $this->_range = $auth['p_type'];
//                    break;
//                }
//            }

        }

        //加载系统配置项
        $this->_setting = CommSetting::getCommSetting(0);

        //写系统日志
        $log_param = Yii::$app->request->post() ? Yii::$app->request->post() : Yii::$app->request->get();
        $logdata = [
            'log_url'=>$url,
            'log_param' => $log_param,
            'company_id' => $this->_user['company_id'],
            'log_ip' => Tools::get_client_ip(),
            'log_uid' => $this->_user['u_id'],
            'log_uname' => $this->_user['u_name'],
            'depart_id' => $this->_user['u_dept_id'],
            'role_id' => $this->_user['u_role_id'],
            'ctime' => date('Y-m-d H:i:s')
        ];
        Yii::$app->redis->rpush('sass_log',json_encode($logdata));
//        Yii::$app->redis->ltrim('sass_log',1,0);
//        var_dump(Yii::$app->redis->llen('sass_log'));

        return parent::beforeAction($action);
    }

}
