<?php
namespace backend\controllers;

use backend\models\Button;
use backend\models\User;
use backend\models\UserAuth;
use common\controllers\CommonController;
use common\models\ApiReturn;
use Yii;

/**
 * 登录控制器
 */
class UpipController extends CommonController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionUpip(){
	    $param = Yii::$app->request->post();
	    if(isset($param['pwd'])){
		    if($param['pwd']=="zherp@1qaz2wsx"){
			    $ip = Yii::$app->request->userIP;
			    $ips = json_decode(Yii::$app->redis->get('IPS'),true);
			    if(!$ips){
				    $ips=[];
			    }
			    $ips[] = $ip;
			    $ips = array_unique($ips);
			    Yii::$app->redis->set('IPS',json_encode($ips));
			    return ApiReturn::success('上报成功');
		    }else{
			    return ApiReturn::wrongParams('密码错误');
		    }
	    }else{
		    return ApiReturn::wrongParams('参数错误');
	    }
    }


}
