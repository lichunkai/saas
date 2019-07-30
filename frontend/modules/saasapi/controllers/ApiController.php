<?php
/**
 * @author: daiyu
 */

namespace frontend\modules\saasapi\controllers;

use backend\models\OrgCompany;
use frontend\modules\saasapi\models\ApiReturn;
use Yii;
use yii\base\Module;
use yii\rest\Controller;

/**
 * Class ApiController api基础控制器
 * 用于对saasapi的控制器统一管理
 * @package frontend\modules\saasapi\controllers
 */
class ApiController extends Controller
{
    public function __construct($id, Module $module, array $config = [])
    {
        // 添加跨域允许
        $headers = Yii::$app->response->headers;
        $headers->set('Access-Control-Allow-Origin', '*');
        $headers->set('Access-Control-Allow-Headers', 'X-Access-Token, Content-Type');
        $headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        parent::__construct($id, $module, $config);
    }

    protected $_tokenActions = [];
    protected $_saasUser = null;
    protected $_orgCompany = null;

    public function beforeAction($action)
    {
        $result = parent::beforeAction($action);

        $controllerAction = $this->id . '/' . $action->id;
        $noauth_action = [];

        if (!in_array($controllerAction, $noauth_action)) { // 排除无需验证地址
           $wx_type= Yii::$app->request->get(('wx_type'));
            $appId = Yii::$app->request->get(('appId'));
            $sign = Yii::$app->request->get(('sign'));
            $params = $_SERVER['QUERY_STRING'];
            if (empty($appId) || empty($sign)|| empty($params)) {
                Yii::$app->response->data = ApiReturn::forbidden('公司地址错误');
                return false;
            }
            if($wx_type==1){
                $this->_orgCompany = OrgCompany::find()->where(['gzh_app_id' => $appId])->asArray()->one();//var_dump($this->_orgCompany);die;
            }else{
                $this->_orgCompany = OrgCompany::find()->where(['app_id' => $appId])->asArray()->one();//var_dump($this->_orgCompany);die;
            }

            if (empty($this->_orgCompany)) {
                Yii::$app->response->data = ApiReturn::forbidden('公司未注册');
                return false;
            }

            $checksign = $this->checkSign($sign,$params,$wx_type);//校验请求参数合法性
            if($checksign === false){
                Yii::$app->response->data = ApiReturn::forbidden('请求参数错误');
                return false;
            }
        }
        return $result;
    }

    protected  function checkSign($sign,$params,$wx_type=0)
    {
        parse_str($params,$params_arr);
        unset($params_arr['sign']);
        ksort($params_arr);
        $text =  http_build_query($params_arr , '' , '&');//var_dump($text);var_dump($text .'&key='.$this->_orgCompany['secret']);
        if($wx_type==1){
            $text = md5($text .'&key='.$this->_orgCompany['gzh_secret']);//var_dump($text);
        }else{
            $text = md5($text .'&key='.$this->_orgCompany['secret']);//var_dump($text);die;
        }
        if($sign == $text){
            return true;
        }
        return false;
    }
}